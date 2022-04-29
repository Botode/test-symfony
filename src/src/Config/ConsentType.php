<?php

namespace App\Config;

enum ConsentType: int {
    case Yes = 1;
    case No = 0;

    public function score(): int
    {
        return match($this) {
            static::Yes => 4,
            static::No => 0,
        };
    }
}