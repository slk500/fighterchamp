<?php

declare(strict_types=1);

namespace AppBundle\Util;

class AgeCategoryConverter
{
    public static function convert(\DateTime $userBirthday): string
    {
        $years = (date_diff($userBirthday, \DateTime::createFromFormat('U', (string)time())))
            ->format("%y");


//        if ($years <= 14) {
//            return 'młodzik'; //have to add to database first
//        }
        if ($years <= 16) {
            return 'kadet';
        } elseif ($years <= 18) {
            return 'junior';
        } else {
            return 'senior';
        }
    }
}
