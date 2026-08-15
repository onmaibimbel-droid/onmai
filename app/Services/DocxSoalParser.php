<?php

namespace App\Services;

use DOMDocument;
use DOMElement;
use DOMNode;
use RuntimeException;
use ZipArchive;

/**
 * Membaca file .docx berisi bank soal pilihan ganda, lalu memecahnya
 * menjadi daftar soal beserta pilihan jawabannya.
 *
 * Dokumen dari guru biasanya tidak memakai penomoran Word, jadi batas antara
 * pertanyaan dan pilihan jawaban ditebak dari isinya: baris yang mengandung
 * kalimat dianggap pertanyaan, baris pendek berisi rumus dianggap pilihan.
 * Karena tebakan bisa meleset, hasilnya selalu ditampilkan sebagai preview
 * dulu, lengkap dengan catatan pada soal yang perlu diperiksa manual.
 */
class DocxSoalParser
{
    private const M = 'http://schemas.openxmlformats.org/officeDocument/2006/math';
    private const W = 'http://schemas.openxmlformats.org/wordprocessingml/2006/main';

    /** Pilihan jawaban yang ditampung database. Sisanya dibuang. */
    public const MAKS_OPSI = 4;

    /** Kalimat sependek ini lebih mungkin pilihan jawaban daripada pertanyaan. */
    private const MIN_PANJANG_PERTANYAAN = 30;

    private const BUKAN_KALIMAT = [
        'atau', 'dan', 'cm', 'kg', 'sin', 'cos', 'tan', 'cot', 'sec',
        'csc', 'log', 'ln', 'lim', 'max', 'min', 'det',
    ];

    public function __construct(private OmmlToMathml $omml = new OmmlToMathml) {}

    /** @return array<int, array<string, mixed>> */
    public function parse(string $path): array
    {
        return $this->kelompokkan($this->blok($path));
    }

    /**
     * Ambil isi dokumen sebagai deretan blok (paragraf dan tabel) sesuai urutan aslinya.
     *
     * @return array<int, array<string, string>>
     */
    private function blok(string $path): array
    {
        $zip = new ZipArchive;

        if ($zip->open($path) !== true) {
            throw new RuntimeException('File tidak bisa dibuka. Pastikan formatnya .docx, bukan .doc lama.');
        }

        $xml = $zip->getFromName('word/document.xml');
        $zip->close();

        if ($xml === false) {
            throw new RuntimeException('Isi dokumen tidak ditemukan. File kemungkinan rusak.');
        }

        $dom = new DOMDocument;
        $sebelumnya = libxml_use_internal_errors(true);
        $dom->loadXML($xml);
        libxml_clear_errors();
        libxml_use_internal_errors($sebelumnya);

        $body = $dom->getElementsByTagNameNS(self::W, 'body')->item(0);

        if (! $body) {
            throw new RuntimeException('Struktur dokumen tidak dikenali.');
        }

        $blok = [];

        foreach ($body->childNodes as $node) {
            if (! $node instanceof DOMElement) {
                continue;
            }

            if ($node->localName === 'p') {
                $html = trim($this->paragraf($node));

                if ($html !== '') {
                    $blok[] = ['tipe' => 'p', 'html' => $html, 'teks' => $this->teks($node)];
                }

                continue;
            }

            if ($node->localName === 'tbl') {
                $blok[] = ['tipe' => 'tabel', 'html' => $this->tabel($node), 'teks' => $this->teks($node)];
            }
        }

        return $blok;
    }

    /** Susun blok menjadi soal: satu pertanyaan diikuti pilihan jawabannya. */
    private function kelompokkan(array $blok): array
    {
        $soal = [];

        foreach ($blok as $b) {
            if ($b['tipe'] === 'tabel') {
                if ($soal) {
                    $soal[count($soal) - 1]['pertanyaan'] .= $b['html'];
                    $soal[count($soal) - 1]['ada_tabel'] = true;
                }

                continue;
            }

            if (! $soal || $this->berupaPertanyaan($b['teks'])) {
                $soal[] = [
                    'pertanyaan' => $this->buangNomor($b['html']),
                    'opsi' => [],
                    'ada_tabel' => false,
                ];

                continue;
            }

            $soal[count($soal) - 1]['opsi'][] = $this->buangHuruf($b['html']);
        }

        return array_map(fn ($s) => $this->rapikan($s), $soal);
    }

    /** Petakan ke kolom a–d dan kumpulkan catatan untuk ditampilkan di preview. */
    private function rapikan(array $soal): array
    {
        $opsi = $soal['opsi'];
        $jumlah = count($opsi);
        $catatan = [];

        if ($jumlah > self::MAKS_OPSI) {
            $dibuang = $jumlah - self::MAKS_OPSI;
            $catatan[] = "Aslinya {$jumlah} pilihan, {$dibuang} pilihan terakhir dibuang karena database hanya menampung "
                . self::MAKS_OPSI . '. Periksa apakah jawaban benarnya ikut terbuang.';
        }

        if ($jumlah < self::MAKS_OPSI) {
            $catatan[] = "Pilihan jawaban cuma terbaca {$jumlah}, seharusnya " . self::MAKS_OPSI . '. Perlu dilengkapi manual.';
        }

        if ($soal['ada_tabel']) {
            $catatan[] = 'Soal ini mengandung tabel. Cek tampilannya setelah diimport.';
        }

        $hasil = [
            'pertanyaan' => $soal['pertanyaan'],
            'jumlah_opsi_asli' => $jumlah,
            'catatan' => $catatan,
            'siap' => $jumlah >= self::MAKS_OPSI,
        ];

        foreach (range(0, self::MAKS_OPSI - 1) as $i) {
            $hasil[chr(97 + $i)] = $opsi[$i] ?? '';
        }

        return $hasil;
    }

    /**
     * Baris dianggap pertanyaan kalau diakhiri titik-titik, ditutup kata penanya,
     * atau berupa kalimat yang cukup panjang.
     *
     * Ambang panjang dipakai supaya pilihan jawaban yang kebetulan berupa kata
     * — misalnya "20 Bola Merah" — tidak ikut terbaca sebagai pertanyaan.
     */
    private function berupaPertanyaan(string $teks): bool
    {
        $teks = trim($teks);

        if ($teks === '') {
            return false;
        }

        if (preg_match('/(\.\.\.|…|\.\.)\s*$/u', $teks)) {
            return true;
        }

        if (preg_match('/\b(adalah|ialah|tentukan|hitunglah|berapa|berikut)\b\s*[\.:…]*\s*$/iu', $teks)) {
            return true;
        }

        preg_match_all('/\p{L}{3,}/u', $teks, $cocok);

        $kata = array_filter(
            $cocok[0],
            fn ($k) => ! in_array(mb_strtolower($k), self::BUKAN_KALIMAT, true)
        );

        return count($kata) >= 2 && mb_strlen($teks) >= self::MIN_PANJANG_PERTANYAAN;
    }

    private function paragraf(DOMElement $p): string
    {
        return $this->isi($p);
    }

    /** Telusuri isi paragraf sesuai urutan, gabungkan teks biasa dan rumus. */
    private function isi(DOMNode $node): string
    {
        $out = '';

        foreach ($node->childNodes as $child) {
            if (! $child instanceof DOMElement) {
                continue;
            }

            if ($child->namespaceURI === self::M) {
                if ($child->localName === 'oMath') {
                    $out .= $this->omml->convert($child);

                    continue;
                }

                if ($child->localName === 'oMathPara') {
                    foreach ($child->childNodes as $m) {
                        if ($m instanceof DOMElement && $m->localName === 'oMath') {
                            $out .= $this->omml->convert($m);
                        }
                    }

                    continue;
                }

                continue;
            }

            if ($child->localName === 'r') {
                $out .= $this->run($child);

                continue;
            }

            if (in_array($child->localName, ['hyperlink', 'smartTag', 'sdt', 'sdtContent', 'ins'], true)) {
                $out .= $this->isi($child);
            }
        }

        return $out;
    }

    private function run(DOMElement $run): string
    {
        $teks = '';

        foreach ($run->childNodes as $child) {
            if (! $child instanceof DOMElement) {
                continue;
            }

            $teks .= match ($child->localName) {
                't' => htmlspecialchars($child->textContent, ENT_QUOTES, 'UTF-8'),
                'tab' => ' ',
                'br' => '<br>',
                default => '',
            };
        }

        if ($teks === '') {
            return '';
        }

        $pr = null;

        foreach ($run->childNodes as $child) {
            if ($child instanceof DOMElement && $child->localName === 'rPr') {
                $pr = $child;

                break;
            }
        }

        if (! $pr) {
            return $teks;
        }

        $punya = function (string $nama) use ($pr): bool {
            foreach ($pr->childNodes as $c) {
                if ($c instanceof DOMElement && $c->localName === $nama) {
                    return $c->getAttributeNS(self::W, 'val') !== '0';
                }
            }

            return false;
        };

        $vertAlign = '';

        foreach ($pr->childNodes as $c) {
            if ($c instanceof DOMElement && $c->localName === 'vertAlign') {
                $vertAlign = $c->getAttributeNS(self::W, 'val');
            }
        }

        if ($vertAlign === 'superscript') {
            $teks = "<sup>{$teks}</sup>";
        } elseif ($vertAlign === 'subscript') {
            $teks = "<sub>{$teks}</sub>";
        }

        if ($punya('b')) {
            $teks = "<strong>{$teks}</strong>";
        }

        if ($punya('i')) {
            $teks = "<em>{$teks}</em>";
        }

        if ($punya('u')) {
            $teks = "<u>{$teks}</u>";
        }

        return $teks;
    }

    private function tabel(DOMElement $tbl): string
    {
        $baris = '';

        foreach ($tbl->childNodes as $tr) {
            if (! $tr instanceof DOMElement || $tr->localName !== 'tr') {
                continue;
            }

            $sel = '';

            foreach ($tr->childNodes as $tc) {
                if (! $tc instanceof DOMElement || $tc->localName !== 'tc') {
                    continue;
                }

                $isi = '';

                foreach ($tc->childNodes as $p) {
                    if ($p instanceof DOMElement && $p->localName === 'p') {
                        $isi .= $this->isi($p) . ' ';
                    }
                }

                $sel .= '<td>' . trim($isi) . '</td>';
            }

            $baris .= "<tr>{$sel}</tr>";
        }

        return "<table><tbody>{$baris}</tbody></table>";
    }

    private function teks(DOMNode $node): string
    {
        $teks = '';

        foreach ($node->childNodes as $child) {
            if ($child instanceof DOMElement
                && in_array($child->localName, ['t'], true)
                && in_array($child->namespaceURI, [self::W, self::M], true)) {
                $teks .= $child->textContent;

                continue;
            }

            if ($child instanceof DOMElement) {
                $teks .= $this->teks($child);
            }
        }

        return trim(preg_replace('/\s+/u', ' ', $teks));
    }

    /** Buang penomoran yang diketik manual, misalnya "37." di awal pertanyaan. */
    private function buangNomor(string $html): string
    {
        return preg_replace('/^\s*\d{1,3}\s*[\.\)]\s*/u', '', $html, 1);
    }

    /** Buang penanda pilihan yang diketik manual, misalnya "A." atau "b)". */
    private function buangHuruf(string $html): string
    {
        return preg_replace('/^\s*[a-eA-E]\s*[\.\)]\s+/u', '', $html, 1);
    }
}
