<?php

namespace Tests\Feature;

use App\Models\Mandiri;
use App\Models\Mapel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class WordImportPreviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_missing_preview_option_is_filled_and_sanitized_before_import(): void
    {
        Storage::fake();

        $guru = User::factory()->create(['role' => 'guru']);
        $mandiri = Mandiri::create([
            'nama_mapel' => 'Matematika',
            'semester' => 1,
            'kelas' => 'X',
            'pelajaran' => 'Matematika',
        ]);
        $token = Str::uuid()->toString();

        Storage::put("word-import/{$token}.json", json_encode([
            'mandiri_id' => $mandiri->id,
            'nama_file' => 'soal.docx',
            'soal' => [[
                'pertanyaan' => '<p>Tentukan nilai x.</p>',
                'a' => '<p>1</p>',
                'b' => '',
                'c' => '<p>3</p>',
                'd' => '<p>4</p>',
            ]],
        ]));

        $response = $this->actingAs($guru)->post(route('mapel.import-word.simpan', [$mandiri, $token]), [
            'pilih' => [0],
            'kunci' => [0 => 'b'],
            'isi' => [0 => ['b' => '<p onclick="alert(1)">2<script>alert(1)</script></p>']],
        ]);

        $response->assertRedirect(route('mandiri.show', $mandiri));

        $mapel = Mapel::sole();

        $this->assertSame('<p>2</p>', $mapel->b);
        $this->assertSame('b', $mapel->kunci);
        Storage::assertMissing("word-import/{$token}.json");
    }
}
