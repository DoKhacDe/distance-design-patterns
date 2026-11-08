<?php

namespace App\Domain\Content;

class ReportingContentFilter implements ContentFilterService
{
    public function __construct(
        private readonly ContentFilterService $service,
    ) {
    }

    public function filter(array $contents, array $filters): array
    {
        $result = $this->service->filter($contents, $filters);

        $totalBefore = count($contents);
        $totalAfter = count($result);

        $report = [
            'total_before' => $totalBefore,
            'total_after' => $totalAfter,
            'filtered_out' => $totalBefore - $totalAfter,
        ];

        logger()->info('Content filtering report', $report);

        return $result;
    }
}
