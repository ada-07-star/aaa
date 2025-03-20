<?php

namespace App\Enums;

enum CurrentStateEnum: string
{
    case DRAFT = 'draft';
    case ACTIVE = 'active';
    case ARCHIVED = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'پیش نویس',
            self::ACTIVE => 'فعال',
            self::ARCHIVED => 'بایگانی شده',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
