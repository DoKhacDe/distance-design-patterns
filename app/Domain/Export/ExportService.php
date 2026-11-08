<?php

namespace App\Domain\Export;

interface ExportService
{
    public function export(array $data): string;
}
