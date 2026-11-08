<?php

namespace App\Domain\Export;

use Throwable;

class ExcelExportHandler extends AbstractExportHandler
{
    public function __construct(
        private readonly ExportStrategy $strategy,
    ) {}

    public function handle(Export $request): ExportResult
    {
        try {
            return new ExportResult(
                success: true,
                content: $this->strategy->export($request->data),
                format: 'excel',
            );
        } catch (Throwable) {
            return $this->next($request);
        }
    }
}
