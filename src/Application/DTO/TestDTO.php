<?php

namespace App\Application\DTO;

readonly class TestDTO
{
    public function __construct(
        public string $status
    ) {
    }
}
