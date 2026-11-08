<?php

namespace App\Domain\Export;

class ExportResult
{
    public function __construct(
        public bool $success,
        public ?string $content = null,
        public ?string $format = null,
        public ?string $message = null,
    ) {
    }
}
