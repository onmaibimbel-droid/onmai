<?php

namespace Tests\Unit;

use App\Services\SanitizedHtml;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class SanitizedHtmlTest extends TestCase
{
    public function test_it_keeps_mathml_and_tables_while_removing_active_markup(): void
    {
        $html = new SanitizedHtml;

        $result = $html->sanitize(
            '<p onclick="alert(1)">Nilai <math display="block"><mfrac><mi>x</mi><mn>2</mn></mfrac></math></p>'
            .'<table><tbody><tr><td colspan="2">Data</td></tr></tbody></table><script>alert(1)</script>'
        );

        $this->assertStringContainsString('<math display="block">', $result);
        $this->assertStringContainsString('<mfrac><mi>x</mi><mn>2</mn></mfrac>', $result);
        $this->assertStringContainsString('<table>', $result);
        $this->assertStringContainsString('colspan="2"', $result);
        $this->assertStringNotContainsString('onclick', $result);
        $this->assertStringNotContainsString('<script', $result);

        $wrappedScript = $html->sanitize('<custom-wrapper><script>alert(1)</script>Jawaban</custom-wrapper>');

        $this->assertSame('Jawaban', $wrappedScript);
    }

    public function test_it_decodes_utf8_editor_payloads_before_sanitizing_them(): void
    {
        $request = Request::create('/', 'POST', [
            'pertanyaan' => 'b64:'.base64_encode('<p>Jumlah &alpha; <img src="javascript:alert(1)"></p>'),
        ]);

        (new SanitizedHtml)->prepare($request, ['pertanyaan']);

        $this->assertSame('<p>Jumlah α <img></p>', $request->input('pertanyaan'));
    }

    public function test_it_rejects_malformed_encoded_payloads(): void
    {
        $request = Request::create('/', 'POST', ['pertanyaan' => 'b64:not-valid']);

        $this->expectException(ValidationException::class);

        (new SanitizedHtml)->prepare($request, ['pertanyaan']);
    }
}
