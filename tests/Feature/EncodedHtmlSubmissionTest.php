<?php

namespace Tests\Feature;

use App\Models\Mandiri;
use App\Models\Mapel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EncodedHtmlSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_mapel_store_decodes_and_sanitizes_editor_fields(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);
        $mandiri = Mandiri::create([
            'nama_mapel' => 'Matematika',
            'semester' => 1,
            'kelas' => 'X',
            'pelajaran' => 'Matematika',
        ]);

        $response = $this->actingAs($guru)->post(route('mapel.store', $mandiri), [
            'pertanyaan' => $this->encoded('<p onclick="alert(1)">Tentukan <math><mfrac><mi>x</mi><mn>2</mn></mfrac></math></p><script>alert(1)</script>'),
            'a' => $this->encoded('<table><tbody><tr><td>1</td></tr></tbody></table>'),
            'b' => $this->encoded('<p>B</p>'),
            'c' => $this->encoded('<p>C</p>'),
            'd' => $this->encoded('<p>D</p>'),
            'pembahasan' => $this->encoded('<p>α adalah konstanta.</p>'),
            'kunci' => 'A',
        ]);

        $response->assertRedirect(route('mandiri.show', $mandiri));

        $mapel = Mapel::sole();

        $this->assertStringContainsString('<math>', $mapel->pertanyaan);
        $this->assertStringContainsString('<mfrac><mi>x</mi><mn>2</mn></mfrac>', $mapel->pertanyaan);
        $this->assertStringContainsString('<table>', $mapel->a);
        $this->assertStringContainsString('α adalah konstanta', $mapel->pembahasan);
        $this->assertStringNotContainsString('onclick', $mapel->pertanyaan);
        $this->assertStringNotContainsString('<script', $mapel->pertanyaan);
    }

    private function encoded(string $html): string
    {
        return 'b64:'.base64_encode($html);
    }
}
