<?php

namespace Tests\AppBundle\Util;

use AppBundle\Util\AgeCategoryConverter;
use PHPUnit\Framework\TestCase;

class AgeCategoryConverterTest extends TestCase
{
    /**
     * @dataProvider getAges()
     */
    public function testShouldConvertUserCategory($age, $expected)
    {
        $actual = AgeCategoryConverter::convert($age);
        self::assertEquals($expected, $actual);
    }

    public function getAges()
    {
        return [
            [13, 'młodzik'],
            [15, 'kadet'],
            [17, 'junior'],
            [20, 'senior'],
        ];
    }
}
