<?php

namespace App\Domain\Export;

class Export
{
    public function __construct(
        public array $data,
    ) {
    }
}
