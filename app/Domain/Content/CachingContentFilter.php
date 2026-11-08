<?php

namespace App\Domain\Content;

use Illuminate\Support\Facades\Cache;

class CachingContentFilter implements ContentFilterService
{
    public function __construct(
        private readonly ContentFilterService $service,
    ) {
    }

    public function filter(array $contents, array $filters): array
    {
        $key = 'content_filter:' . hash(
                'sha256',
                serialize([$contents, $filters]),
            );

        return Cache::remember(
            $key,
            now()->addMinutes(10),
            fn() => $this->service->filter($contents, $filters),
        );
    }
}
