<?php

namespace App\Domain\Export;

abstract class AbstractExportHandler implements ExportHandler
{
    private ?ExportHandler $nextHandler = null;

    public function setNext(ExportHandler $handler): ExportHandler
    {
        $this->nextHandler = $handler;

        return $handler;
    }

    abstract public function handle(Export $request): ExportResult;

    protected function next(Export $request): ExportResult
    {
        return $this->nextHandler?->handle($request)
            ?? new ExportResult(
                success: false,
                message: 'Không có phương pháp xuất dữ liệu phù hợp.',
            );
    }
}
