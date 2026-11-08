<?php

namespace App\Domain\Export;

final class ExportHandlerChain
{
    public function __construct(
        private readonly ExportHandler $firstHandler,
    ) {}

    public function handle(Export $request): ExportResult
    {
        return $this->firstHandler->handle($request);
    }
}
