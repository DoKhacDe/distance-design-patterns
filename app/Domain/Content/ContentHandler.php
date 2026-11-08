<?php

namespace App\Domain\Content;

interface ContentHandler
{
    public function setNext(ContentHandler $handler): ContentHandler;

    public function handle(ContentFilter $request): void;
}
