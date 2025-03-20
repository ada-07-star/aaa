<?php

namespace App\Enums;

enum ParticipationTypeEnum: string
{
    case  INDIVIDUAL = 'individual';
    case  TEAM = 'team';

    public function label(): string
    {
        return match ($this) {
            self::INDIVIDUAL => 'فردی',
            self::TEAM => 'تیمی',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
