<?php

namespace App\Enums;

enum CurrentStateEnum
{
    const ACTIVE = 'active';  
    const INACTIVE = 'inactive';  
    const PENDING = 'pending';  

    // متد برای بازگرداندن تمام مقادیر  
    public static function getValues()  
    {  
        return [  
            self::ACTIVE,  
            self::INACTIVE,  
            self::PENDING,  
        ];  
    }  
}
