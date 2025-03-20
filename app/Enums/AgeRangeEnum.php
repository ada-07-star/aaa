<?php

namespace App\Enums;

enum AgeRangeEnum: string
{
    case CHILD = 'child';  
    case TEEN = 'teen';  
    case ADULT = 'adult';  

    public function label(): string
    {  
        return match ($this) {
            self::CHILD => 'کودک',  
            self::TEEN=> 'نوجوان',  
            self::ADULT=> 'مسن',
        };
    }  

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
