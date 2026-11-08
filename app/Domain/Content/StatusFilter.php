<?php

namespace App\Domain\Content;

use App\Domain\Content\ContentFilterStrategy;

class StatusFilter implements ContentFilterStrategy
{
    public function filter(array $contents, mixed $value): array
    {
        return array_values(
            array_filter(
                $contents,
                fn(array $content): bool => $content['status'] === $value,
            )
        );
    }
}
