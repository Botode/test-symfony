<?php

namespace App\Enum;

enum EducationType: string
{
    case Higher = 'higher';
    case Special = 'special';
    case Secondary = 'secondary';

    public function label(): string
    {
        return match ($this) {
            static::Higher => 'education.higher',
            static::Special => 'education.special',
            static::Secondary => 'education.secondary',
        };
    }

    public function score(): int
    {
        return match ($this) {
            static::Higher => 15,
            static::Special => 10,
            static::Secondary => 5,
        };
    }
}
