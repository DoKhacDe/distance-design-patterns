<?php

namespace App\Domain\Export;

interface ExportStrategy
{
    public function export(array $data): string;
}
