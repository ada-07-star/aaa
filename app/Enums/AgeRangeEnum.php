<?php

namespace App\Enums;

enum AgeRangeEnum
{
    const CHILD = 'child';  
    const TEEN = 'teen';  
    const ADULT = 'adult';  

    // علاوه بر این، می‌توانید متدهایی برای بازگرداندن تمام مقادیر اضافه کنید  
    public static function getValues()  
    {  
        return [  
            self::CHILD,  
            self::TEEN,  
            self::ADULT,  
        ];  
    }  
}
