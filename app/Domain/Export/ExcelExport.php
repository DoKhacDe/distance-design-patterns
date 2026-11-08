<?php

namespace App\Domain\Export;

use App\Domain\Export\ExportStrategy;

class ExcelExport implements ExportStrategy
{
    public function export(array $data): string
    {
        return json_encode($data, JSON_THROW_ON_ERROR);
    }
}
