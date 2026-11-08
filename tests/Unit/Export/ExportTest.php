<?php

namespace Tests\Unit\Export;

use App\Domain\Export\CompressionExport;
use App\Domain\Export\CsvExport;
use App\Domain\Export\EncryptionExport;
use App\Domain\Export\ExcelExport;
use App\Domain\Export\ExportService;
use App\Domain\Export\PdfExport;
use App\Domain\Export\SignatureExport;
use Mockery;
use Tests\TestCase;

final class ExportTest extends TestCase
{
    private function createData(): array
    {
        return [
            ['name' => 'John', 'age' => 25],
            ['name' => 'Anna', 'age' => 30],
        ];
    }

    public function test_csv_export_returns_csv_content(): void
    {
        $result = (new CsvExport())->export($this->createData());

        $this->assertStringContainsString('name,age', $result);
        $this->assertStringContainsString('John,25', $result);
        $this->assertStringContainsString('Anna,30', $result);
    }

    public function test_excel_export_returns_json_content(): void
    {
        $result = (new ExcelExport())->export($this->createData());

        $this->assertJson($result);
    }

    public function test_pdf_export_returns_pdf_placeholder(): void
    {
        $result = (new PdfExport())->export($this->createData());

        $this->assertStringStartsWith('PDF: ', $result);
    }

    public function test_compression_export_compresses_content(): void
    {
        $service = Mockery::mock(ExportService::class);

        $service
            ->shouldReceive('export')
            ->once()
            ->andReturn('export data');

        $decorator = new CompressionExport($service);

        $result = $decorator->export($this->createData());

        $this->assertSame('export data', gzdecode($result));
    }

    public function test_encryption_export_encrypts_content(): void
    {
        $service = Mockery::mock(ExportService::class);

        $service
            ->shouldReceive('export')
            ->once()
            ->andReturn('export data');

        $key = random_bytes(32);

        $decorator = new EncryptionExport($service, $key);

        $result = $decorator->export($this->createData());

        $iv = substr($result, 0, 12);
        $tag = substr($result, 12, 16);
        $encrypted = substr($result, 28);

        $decrypted = openssl_decrypt(
            $encrypted,
            'aes-256-gcm',
            $key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag,
        );

        $this->assertSame('export data', $decrypted);
    }

    public function test_signature_export_adds_signature(): void
    {
        $service = Mockery::mock(ExportService::class);

        $service
            ->shouldReceive('export')
            ->once()
            ->andReturn('export data');

        $secret = 'test-secret';

        $decorator = new SignatureExport($service, $secret);

        $result = $decorator->export($this->createData());

        $content = substr($result, 0, -32);
        $signature = substr($result, -32);

        $expectedSignature = hash_hmac(
            'sha256',
            $content,
            $secret,
            true,
        );

        $this->assertSame('export data', $content);
        $this->assertSame($expectedSignature, $signature);
    }
}
