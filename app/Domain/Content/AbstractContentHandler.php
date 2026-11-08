<?php

namespace App\Domain\Content;

abstract class AbstractContentHandler implements ContentHandler
{
    private ?ContentHandler $nextHandler = null;

    public function setNext(ContentHandler $handler): ContentHandler
    {
        $this->nextHandler = $handler;

        return $handler;
    }

    abstract public function handle(ContentFilter $request): void;

    protected function next(ContentFilter $request): void
    {
        $this->nextHandler?->handle($request);
    }
}
