<?php

declare(strict_types=1);

namespace AppBundle\Util;

class AgeCategoryConverter
{
    public static function convert(int $years): string
    {
        if ($years <= 14) {
            return 'młodzik';
        } elseif ($years <= 16) {
            return 'kadet';
        } elseif ($years <= 18) {
            return 'junior';
        } else {
            return 'senior';
        }
    }
}
