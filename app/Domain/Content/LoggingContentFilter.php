<?php

namespace App\Domain\Content;

use Psr\Log\LoggerInterface;

class LoggingContentFilter implements ContentFilterService
{
    public function __construct(
        private readonly ContentFilterService $service,
        private readonly LoggerInterface $logger,
    ) {}

    public function filter(array $contents, array $filters): array
    {
        $this->logger->info('Bắt đầu lọc nội dung', [
            'total' => count($contents),
            'filters' => $filters,
        ]);

        $result = $this->service->filter($contents, $filters);

        $this->logger->info('Hoàn thành lọc nội dung', [
            'total' => count($result),
        ]);

        return $result;
    }
}
