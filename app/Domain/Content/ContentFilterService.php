<?php

namespace App\Domain\Content;

interface ContentFilterService
{
    public function filter(array $contents, array $filters): array;
}
