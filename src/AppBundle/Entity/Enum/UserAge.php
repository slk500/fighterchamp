<?php

namespace AppBundle\Entity\Enum;

use MyCLabs\Enum\Enum;

class UserAge extends Enum
{
    const YOUNGSTER = 'młodzik';
    const CADET = 'kadet';
    const JUNIOR = 'junior';
    const SENIOR = 'senior';
}
