<?php

namespace App\Domain\Content;


class BasicContentFilterService implements ContentFilterService
{
    public function __construct(
        private readonly ContentFilterChain $chain,
    ) {}

    public function filter(array $contents, array $filters): array
    {
        return $this->chain->handle(
            new ContentFilter($contents, $filters),
        );
    }
}
