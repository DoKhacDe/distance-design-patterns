<?php

namespace App\Domain\Content;

class KeywordFilterHandler extends AbstractContentHandler
{
    public function __construct(
        private readonly KeywordFilter $filter,
    ) {}

    public function handle(ContentFilter $request): void
    {
        if (isset($request->filters['keyword'])) {
            $request->contents = $this->filter->filter(
                $request->contents,
                $request->filters['keyword'],
            );
        }

        $this->next($request);
    }
}
