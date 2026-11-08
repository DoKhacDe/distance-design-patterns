<?php

namespace App\Domain\Content;

class ContentFilter
{
    public function __construct(
        public array $contents,
        public readonly array $filters,
    ) {
    }
}
