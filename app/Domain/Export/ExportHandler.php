<?php

namespace App\Domain\Export;

interface ExportHandler
{
    public function setNext(ExportHandler $handler): ExportHandler;

    public function handle(Export $request): ExportResult;
}
