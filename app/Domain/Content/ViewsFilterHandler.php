<?php

namespace App\Domain\Content;

class ViewsFilterHandler extends AbstractContentHandler
{
    public function __construct(
        private readonly ViewsFilter $filter,
    ) {}

    public function handle(ContentFilter $request): void
    {
        if (isset($request->filters['min_views'])) {
            $request->contents = $this->filter->filter(
                $request->contents,
                $request->filters['min_views'],
            );
        }

        $this->next($request);
    }
}
