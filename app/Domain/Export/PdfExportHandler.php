<?php

namespace App\Domain\Export;

use Throwable;

class PdfExportHandler extends AbstractExportHandler
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
                format: 'pdf',
            );
        } catch (Throwable) {
            return $this->next($request);
        }
    }
}
