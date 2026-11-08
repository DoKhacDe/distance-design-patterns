<?php

namespace App\Domain\Distance;

use InvalidArgumentException;

class Point
{
    public function __construct(
        public float $lat,
        public float $long,
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        if (!is_finite($this->lat)) {
            throw new InvalidArgumentException(
                'Vĩ độ phải là một số hữu hạn.'
            );
        }

        if (!is_finite($this->long)) {
            throw new InvalidArgumentException(
                'Kinh độ phải là một số hữu hạn.'
            );
        }

        if ($this->lat < -90 || $this->lat > 90) {
            throw new InvalidArgumentException(
                'Vĩ độ phải nằm trong khoảng từ -90 đến 90.'
            );
        }

        if ($this->long < -180 || $this->long > 180) {
            throw new InvalidArgumentException(
                'Kinh độ phải nằm trong khoảng từ -180 đến 180.'
            );
        }
    }

    public function getLat(): float
    {
        return $this->lat;
    }

    public function getLong(): float
    {
        return $this->long;
    }
}
