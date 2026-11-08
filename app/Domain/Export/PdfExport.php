<?php

namespace App\Domain\Export;

use App\Domain\Export\ExportStrategy;

class PdfExport implements ExportStrategy
{
    public function export(array $data): string
    {
        return 'PDF: ' . json_encode($data, JSON_THROW_ON_ERROR);
    }
}
