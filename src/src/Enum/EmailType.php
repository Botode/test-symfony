<?php

namespace App\Enum;

enum EmailType: string {
    case Gmail = 'gmail';
    case Yandex = 'yandex';
    case Mail = 'mail';
    case Other = 'other';

    public function score(): int
    {
        return match($this) {
            static::Gmail => 10,
            static::Yandex => 8,
            static::Mail => 6,
            static::Other => 3,
        };
    }

    public static function fromDomain(string $domain): static
    {
        return match($domain) {
            'gmail' => static::Gmail,
            'yandex' => static::Yandex,
            'mail' => static::Mail,
            default => static::Other,
        };
    }
}