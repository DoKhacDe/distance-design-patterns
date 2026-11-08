<?php

namespace App\Domain\Content;

class StatusFilterHandler extends AbstractContentHandler
{
    public function __construct(
        private readonly StatusFilter $filter,
    ) {}

    public function handle(ContentFilter $request): void
    {
        if (isset($request->filters['status'])) {
            $request->contents = $this->filter->filter(
                $request->contents,
                $request->filters['status'],
            );
        }

        $this->next($request);
    }
}
