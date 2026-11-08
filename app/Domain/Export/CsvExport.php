<?php

namespace App\Domain\Export;

use App\Domain\Export\ExportStrategy;

class CsvExport implements ExportStrategy
{
    public function export(array $data): string
    {
        $output = fopen('php://temp', 'r+');

        if ($data === []) {
            return '';
        }

        fputcsv($output, array_keys($data[0]));

        foreach ($data as $row) {
            fputcsv($output, $row);
        }

        rewind($output);

        $content = stream_get_contents($output);

        fclose($output);

        return $content;
    }
}
