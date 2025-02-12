<?php

namespace App\Enums;

enum ParticipationTypeEnum
{
    const INDIVIDUAL = 'individual';  
    const GROUP = 'group';  
    const CORPORATE = 'corporate';  

    // متد برای بازگرداندن تمام مقادیر  
    public static function getValues()  
    {  
        return [  
            self::INDIVIDUAL,  
            self::GROUP,  
            self::CORPORATE,  
        ];  
    }  
}
