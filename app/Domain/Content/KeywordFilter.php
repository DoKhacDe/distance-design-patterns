<?php

namespace App\Domain\Content;

use App\Domain\Content\ContentFilterStrategy;

class KeywordFilter implements ContentFilterStrategy
{
    public function filter(array $contents, mixed $value): array
    {
        return array_values(
            array_filter(
                $contents,
                fn(array $content): bool => str_contains(
                    strtolower($content['title']),
                    strtolower((string)$value),
                ),
            )
        );
    }
}
