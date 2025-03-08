<?php

namespace App\Enums;

final class CurrentStateEnum
{
    const  DRAFT = 'پیش نویس';
    const  ACTIVE =  'فعال';
    const  ARCHIVED =  'بایگانی شده';

    public static function getValues()
    {
        return [
            self::DRAFT,
            self::ACTIVE,
            self::ARCHIVED,
        ];
    }
}
