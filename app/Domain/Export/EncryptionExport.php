<?php

namespace App\Domain\Export;

use App\Domain\Export\ExportService;
use RuntimeException;

class EncryptionExport implements ExportService
{
    public function __construct(
        private readonly ExportService $service,
        private readonly string $key,
    ) {
    }

    public function export(array $data): string
    {
        $content = $this->service->export($data);

        $iv = random_bytes(12);

        $encrypted = openssl_encrypt(
            $content,
            'aes-256-gcm',
            $this->key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag,
        );

        if ($encrypted === false) {
            throw new RuntimeException('Không thể mã hóa dữ liệu.');
        }

        return $iv . $tag . $encrypted;
    }
}
