<?php

namespace Tests\AppBundle\Util;

use AppBundle\Util\AgeCategoryConverter;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\PhpUnit\ClockMock;

/**
 * @group time-sensitive
 */
class AgeCategoryConverterTest extends TestCase
{
    /**
     * @dataProvider getAges()
     */
    public function testShouldConvertUserCategory($age, $expected)
    {
        ClockMock::withClockMock(strtotime('2000-01-01 12:00:00'));

        $actual = AgeCategoryConverter::convert($age);
        self::assertEquals($expected, $actual);
    }

    public function getAges()
    {
        return [
            [\DateTime::createFromFormat('Y-m-d', '2013-01-01'), 'młodzik'],
            [\DateTime::createFromFormat('Y-m-d', '2016-01-01'), 'kadet'],
            [\DateTime::createFromFormat('Y-m-d', '2018-01-01'), 'junior'],
            [\DateTime::createFromFormat('Y-m-d', '2020-01-01'), 'senior']
        ];
    }
}
