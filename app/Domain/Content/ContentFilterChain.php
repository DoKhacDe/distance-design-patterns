<?php

namespace App\Domain\Content;

final class ContentFilterChain
{
    public function __construct(
        private readonly AbstractContentHandler $firstHandler,
    ) {}

    public function handle(ContentFilter $request): array
    {
        $this->firstHandler->handle($request);

        return $request->contents;
    }
}
