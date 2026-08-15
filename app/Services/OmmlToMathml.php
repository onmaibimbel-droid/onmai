<?php

namespace App\Services;

use DOMElement;
use DOMNode;

/**
 * Mengubah persamaan Word (OMML) menjadi MathML.
 *
 * Guru sering mengetik seluruh kalimat soal di dalam equation editor Word,
 * jadi satu blok <m:oMath> bisa berisi campuran kalimat biasa dan rumus.
 * Karena itu hasilnya bukan satu <math> utuh, melainkan campuran teks HTML
 * biasa dan potongan <math> — supaya kalimatnya tetap tampil tegak dan
 * berspasi normal, bukan miring seperti variabel matematika.
 */
class OmmlToMathml
{
    private const M = 'http://schemas.openxmlformats.org/officeDocument/2006/math';
    private const W = 'http://schemas.openxmlformats.org/wordprocessingml/2006/main';

    private const MATHML_NS = 'http://www.w3.org/1998/Math/MathML';

    /** Kata pendek yang lazim di dalam rumus, jadi tidak dihitung sebagai kalimat. */
    private const BUKAN_KALIMAT = [
        'atau', 'dan', 'cm', 'kg', 'sin', 'cos', 'tan', 'cot', 'sec',
        'csc', 'log', 'ln', 'lim', 'max', 'min', 'det',
    ];

    public function convert(DOMElement $oMath): string
    {
        $html = '';
        $math = '';

        foreach ($oMath->childNodes as $child) {
            if (! $child instanceof DOMElement) {
                continue;
            }

            // Run di level teratas bisa berupa kalimat biasa; kalau iya,
            // tutup dulu blok matematika yang sedang terbuka.
            if ($this->local($child) === 'r') {
                $teks = $this->runText($child);

                if ($teks === '') {
                    continue;
                }

                if ($this->berupaKalimat($teks)) {
                    $html .= $this->tutup($math);
                    $html .= htmlspecialchars($teks, ENT_QUOTES, 'UTF-8');

                    continue;
                }

                $math .= $this->tokenisasi($teks);

                continue;
            }

            $math .= $this->node($child);
        }

        return $html . $this->tutup($math);
    }

    /** Bungkus token yang menumpuk jadi satu elemen <math>, lalu kosongkan. */
    private function tutup(string &$math): string
    {
        if (trim($math) === '') {
            $math = '';

            return '';
        }

        $hasil = '<math xmlns="' . self::MATHML_NS . '"><mrow>' . $math . '</mrow></math>';
        $math = '';

        return $hasil;
    }

    private function node(DOMNode $node): string
    {
        if (! $node instanceof DOMElement) {
            return '';
        }

        return match ($this->local($node)) {
            'r'         => $this->tokenisasi($this->runText($node)),
            'f'         => $this->pecahan($node),
            'sSup'      => $this->skrip($node, 'msup', ['e', 'sup']),
            'sSub'      => $this->skrip($node, 'msub', ['e', 'sub']),
            'sSubSup'   => $this->skrip($node, 'msubsup', ['e', 'sub', 'sup']),
            'sPre'      => $this->skrip($node, 'mmultiscripts', ['e', 'sub', 'sup']),
            'rad'       => $this->akar($node),
            'd'         => $this->kurung($node),
            'nary'      => $this->nary($node),
            'func'      => $this->fungsi($node),
            'limLow'    => $this->skrip($node, 'munder', ['e', 'lim']),
            'limUpp'    => $this->skrip($node, 'mover', ['e', 'lim']),
            'bar'       => $this->garis($node),
            'acc'       => $this->aksen($node),
            'groupChr'  => $this->groupChr($node),
            'm'         => $this->matriks($node),
            'eqArr'     => $this->deret($node),
            'box',
            'borderBox' => $this->anak($node),
            'e', 'num', 'den', 'sup', 'sub', 'deg', 'lim', 'fName'
                        => $this->anak($node),
            default     => $this->anak($node),
        };
    }

    /** Gabungkan hasil konversi seluruh anak elemen. */
    private function anak(DOMElement $node): string
    {
        $out = '';

        foreach ($node->childNodes as $child) {
            if ($child instanceof DOMElement && $this->isMath($child)) {
                $out .= $this->node($child);
            }
        }

        return $out;
    }

    /** Isi satu sub-elemen tertentu, dibungkus <mrow> supaya strukturnya aman. */
    private function bagian(DOMElement $node, string $nama): string
    {
        $el = $this->cari($node, $nama);

        return '<mrow>' . ($el ? $this->anak($el) : '') . '</mrow>';
    }

    private function pecahan(DOMElement $node): string
    {
        // Word memakai m:type val="lin" untuk pecahan yang ditulis miring (a/b).
        $tipe = $this->cari($node, 'fPr');
        $tipe = $tipe ? $this->cari($tipe, 'type') : null;

        if ($tipe && $tipe->getAttributeNS(self::M, 'val') === 'lin') {
            return $this->bagian($node, 'num') . '<mo>/</mo>' . $this->bagian($node, 'den');
        }

        return '<mfrac>' . $this->bagian($node, 'num') . $this->bagian($node, 'den') . '</mfrac>';
    }

    private function skrip(DOMElement $node, string $tag, array $bagian): string
    {
        $isi = '';

        foreach ($bagian as $nama) {
            $isi .= $this->bagian($node, $nama);
        }

        return "<{$tag}>{$isi}</{$tag}>";
    }

    private function akar(DOMElement $node): string
    {
        $pr = $this->cari($node, 'radPr');
        $sembunyi = $pr ? $this->cari($pr, 'degHide') : null;
        $deg = $this->cari($node, 'deg');

        $derajatKosong = ! $deg || trim($this->anak($deg)) === '';

        if (($sembunyi && $sembunyi->getAttributeNS(self::M, 'val') !== '0') || $derajatKosong) {
            return '<msqrt>' . $this->bagian($node, 'e') . '</msqrt>';
        }

        return '<mroot>' . $this->bagian($node, 'e') . $this->bagian($node, 'deg') . '</mroot>';
    }

    private function kurung(DOMElement $node): string
    {
        $pr = $this->cari($node, 'dPr');

        $buka = $this->atribut($pr, 'begChr', '(');
        $tutup = $this->atribut($pr, 'endChr', ')');
        $pemisah = $this->atribut($pr, 'sepChr', '|');

        $isi = [];

        foreach ($node->childNodes as $child) {
            if ($child instanceof DOMElement && $this->local($child) === 'e') {
                $isi[] = $this->anak($child);
            }
        }

        $tengah = implode('<mo>' . $this->esc($pemisah) . '</mo>', $isi);

        // Kurung kosong (begChr="") dipakai Word untuk grup tanpa tanda kurung.
        $kiri = $buka === '' ? '' : '<mo>' . $this->esc($buka) . '</mo>';
        $kanan = $tutup === '' ? '' : '<mo>' . $this->esc($tutup) . '</mo>';

        return '<mrow>' . $kiri . $tengah . $kanan . '</mrow>';
    }

    private function nary(DOMElement $node): string
    {
        $pr = $this->cari($node, 'naryPr');

        $simbol = $this->atribut($pr, 'chr', '∫');
        $lokasi = $this->atribut($pr, 'limLoc', 'subSup');

        $adaSub = ! $this->tersembunyi($pr, 'subHide');
        $adaSup = ! $this->tersembunyi($pr, 'supHide');

        $op = '<mo>' . $this->esc($simbol) . '</mo>';

        if ($adaSub && $adaSup) {
            $tag = $lokasi === 'undOvr' ? 'munderover' : 'msubsup';
            $op = "<{$tag}>{$op}" . $this->bagian($node, 'sub') . $this->bagian($node, 'sup') . "</{$tag}>";
        } elseif ($adaSub) {
            $tag = $lokasi === 'undOvr' ? 'munder' : 'msub';
            $op = "<{$tag}>{$op}" . $this->bagian($node, 'sub') . "</{$tag}>";
        } elseif ($adaSup) {
            $tag = $lokasi === 'undOvr' ? 'mover' : 'msup';
            $op = "<{$tag}>{$op}" . $this->bagian($node, 'sup') . "</{$tag}>";
        }

        return $op . $this->bagian($node, 'e');
    }

    private function fungsi(DOMElement $node): string
    {
        return $this->bagian($node, 'fName') . '<mo>&#8289;</mo>' . $this->bagian($node, 'e');
    }

    private function garis(DOMElement $node): string
    {
        $pr = $this->cari($node, 'barPr');
        $posisi = $this->atribut($pr, 'pos', 'top');

        $tag = $posisi === 'bot' ? 'munder' : 'mover';

        return "<{$tag}>" . $this->bagian($node, 'e') . '<mo>&#175;</mo>' . "</{$tag}>";
    }

    private function aksen(DOMElement $node): string
    {
        $pr = $this->cari($node, 'accPr');
        $simbol = $this->atribut($pr, 'chr', '̂');

        return '<mover>' . $this->bagian($node, 'e') . '<mo>' . $this->esc($simbol) . '</mo></mover>';
    }

    private function groupChr(DOMElement $node): string
    {
        $pr = $this->cari($node, 'groupChrPr');
        $simbol = $this->atribut($pr, 'chr', '⏟');
        $posisi = $this->atribut($pr, 'pos', 'bot');

        $tag = $posisi === 'top' ? 'mover' : 'munder';

        return "<{$tag}>" . $this->bagian($node, 'e') . '<mo>' . $this->esc($simbol) . '</mo>' . "</{$tag}>";
    }

    private function matriks(DOMElement $node): string
    {
        $baris = '';

        foreach ($node->childNodes as $mr) {
            if (! $mr instanceof DOMElement || $this->local($mr) !== 'mr') {
                continue;
            }

            $sel = '';

            foreach ($mr->childNodes as $e) {
                if ($e instanceof DOMElement && $this->local($e) === 'e') {
                    $sel .= '<mtd>' . $this->anak($e) . '</mtd>';
                }
            }

            $baris .= '<mtr>' . $sel . '</mtr>';
        }

        return '<mtable>' . $baris . '</mtable>';
    }

    private function deret(DOMElement $node): string
    {
        $baris = '';

        foreach ($node->childNodes as $e) {
            if ($e instanceof DOMElement && $this->local($e) === 'e') {
                $baris .= '<mtr><mtd>' . $this->anak($e) . '</mtd></mtr>';
            }
        }

        return '<mtable>' . $baris . '</mtable>';
    }

    /**
     * Pecah teks jadi token MathML.
     *
     * Angka jadi <mn>, satu huruf jadi <mi> (variabel, tampil miring),
     * dua huruf atau lebih jadi <mtext> (nama fungsi atau kata, tampil tegak),
     * sisanya jadi <mo>.
     */
    private function tokenisasi(string $teks): string
    {
        if ($teks === '') {
            return '';
        }

        $out = '';
        $token = preg_split(
            '/(\d+(?:[.,]\d+)?|[\p{L}]+|\s+)/u',
            $teks,
            -1,
            PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY
        );

        foreach ($token as $t) {
            if (trim($t) === '') {
                $out .= '<mspace width="0.25em"/>';

                continue;
            }

            if (preg_match('/^\d+(?:[.,]\d+)?$/u', $t)) {
                $out .= '<mn>' . $this->esc($t) . '</mn>';

                continue;
            }

            if (preg_match('/^\p{L}+$/u', $t)) {
                $out .= mb_strlen($t) === 1
                    ? '<mi>' . $this->esc($t) . '</mi>'
                    : '<mtext>' . $this->esc($t) . '</mtext>';

                continue;
            }

            // Sisa karakter dipecah satu per satu supaya tiap operator berdiri sendiri.
            foreach (preg_split('//u', $t, -1, PREG_SPLIT_NO_EMPTY) as $ch) {
                $out .= '<mo>' . $this->esc($ch) . '</mo>';
            }
        }

        return $out;
    }

    /** Teks dianggap kalimat kalau punya dua kata atau lebih di luar istilah rumus. */
    private function berupaKalimat(string $teks): bool
    {
        preg_match_all('/\p{L}{3,}/u', $teks, $cocok);

        $kata = array_filter(
            $cocok[0],
            fn ($k) => ! in_array(mb_strtolower($k), self::BUKAN_KALIMAT, true)
        );

        return count($kata) >= 2;
    }

    private function runText(DOMElement $run): string
    {
        $teks = '';

        foreach ($run->childNodes as $child) {
            if ($child instanceof DOMElement && $this->local($child) === 't') {
                $teks .= $child->textContent;
            }
        }

        return $teks;
    }

    private function cari(?DOMElement $node, string $nama): ?DOMElement
    {
        if (! $node) {
            return null;
        }

        foreach ($node->childNodes as $child) {
            if ($child instanceof DOMElement && $this->local($child) === $nama) {
                return $child;
            }
        }

        return null;
    }

    private function atribut(?DOMElement $pr, string $nama, string $default): string
    {
        $el = $this->cari($pr, $nama);

        if (! $el) {
            return $default;
        }

        $nilai = $el->getAttributeNS(self::M, 'val');

        return $nilai === '' && ! $el->hasAttributeNS(self::M, 'val') ? $default : $nilai;
    }

    private function tersembunyi(?DOMElement $pr, string $nama): bool
    {
        $el = $this->cari($pr, $nama);

        if (! $el) {
            return false;
        }

        $val = $el->getAttributeNS(self::M, 'val');

        return $val !== '0' && $val !== 'false';
    }

    private function isMath(DOMElement $node): bool
    {
        return $node->namespaceURI === self::M;
    }

    private function local(DOMNode $node): string
    {
        return $node->localName ?? '';
    }

    private function esc(string $teks): string
    {
        return htmlspecialchars($teks, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }
}
