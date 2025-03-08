<?php

namespace App\Enums;

enum ParticipationTypeEnum
{
    const INDIVIDUAL = 'فردی';
    const TEAM = 'تیمی';  
  
    public static function getValues()
    {  
        return [
            self::INDIVIDUAL,
            self::TEAM,
        ];
    }  
}
