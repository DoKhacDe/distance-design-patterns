<?php

namespace App\Domain\Export;

use App\Domain\Export\ExportService;

class SignatureExport implements ExportService
{
    public function __construct(
        private readonly ExportService $service,
        private readonly string $secret,
    ) {}

    public function export(array $data): string
    {
        $content = $this->service->export($data);

        $signature = hash_hmac(
            'sha256',
            $content,
            $this->secret,
            true,
        );

        return $content . $signature;
    }
}
