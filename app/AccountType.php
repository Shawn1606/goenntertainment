<?php

namespace App;

enum AccountType: string
{
    case Personal = 'personal';
    case Business = 'business';

    public function label(): string
    {
        return match ($this) {
            self::Personal => 'Persönlich',
            self::Business => 'Business',
        };
    }
}
