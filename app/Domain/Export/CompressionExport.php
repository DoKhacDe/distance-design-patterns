<?php

namespace App\Domain\Export;

use App\Domain\Export\ExportService;
use RuntimeException;

class CompressionExport implements ExportService
{
    public function __construct(
        private readonly ExportService $service,
    ) {
    }

    public function export(array $data): string
    {
        $content = $this->service->export($data);

        $compressed = gzencode($content);

        if ($compressed === false) {
            throw new RuntimeException('Không thể nén dữ liệu.');
        }

        return $compressed;
    }
}
