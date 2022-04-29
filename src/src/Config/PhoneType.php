<?php

namespace App\Config;

enum PhoneType: string {
    case Megafon = 'megafon';
    case Beeline = 'beeline';
    case Mts = 'mts';
    case Other = 'other';

    public function score(): int
    {
        return match($this) {
            static::Megafon => 10,
            static::Beeline => 5,
            static::Mts => 3,
            static::Other => 1,
        };
    }

    public static function fromPrefix(int $prefix): static
    {
        return match(true) {
            (902 <= $prefix && $prefix <= 906) => static::Beeline,
            (910 <= $prefix && $prefix <= 919) => static::Mts,
            (920 <= $prefix && $prefix <= 929) => static::Megafon,
            (930 <= $prefix && $prefix <= 939) => static::Megafon,
            (960 <= $prefix && $prefix <= 969) => static::Beeline,
            (980 <= $prefix && $prefix <= 989) => static::Mts,
            default => static::Other,
        };
    }

    public static function fromOper(string $oper): static
    {
        return match($oper) {
            'MTC' => static::Mts,
            'МегаФон' => static::Megafon,
            '' => static::Beeline,
            default => static::Other,
        };
    }
}