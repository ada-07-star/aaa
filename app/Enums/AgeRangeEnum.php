<?php

namespace App\Enums;

enum AgeRangeEnum
{
    const CHILD = 'child';  
    const TEEN = 'teen';  
    const ADULT = 'adult';  

    public static function getValues()  
    {  
        return [  
            self::CHILD,  
            self::TEEN,  
            self::ADULT,  
        ];  
    }  
}
