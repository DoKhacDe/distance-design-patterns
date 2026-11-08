<?php

namespace App\Domain\Content;

interface ContentFilterStrategy
{
    public function filter(array $contents, mixed $value): array;
}
