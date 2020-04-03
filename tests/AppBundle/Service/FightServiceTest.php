<?php

namespace Tests\AppBundle\Service;

use AppBundle\Entity\SignupTournament;
use AppBundle\Service\FightService;
use PHPUnit\Framework\TestCase;

class FightServiceTest extends TestCase
{
    /**
     * @dataProvider additionProvider
     */
    public function testGetHighestFormula($signUp0Formula, $signUp1Formula, $expected)
    {
        $signUp = $this->getMockBuilder(SignupTournament::class)
            ->setMethods(['getFormula'])
            ->disableOriginalConstructor()
            ->getMock();

        $fightService = $this->getMockBuilder(FightService::class)
            ->setMethodsExcept(['getHighestFormula'])
            ->disableOriginalConstructor()
            ->getMock();

        /**
         * @var $signUp0\AppBundle\Entity\SignupTournament
         */
        $signUp0 = clone $signUp;

        /**
         * @var $signUp1\AppBundle\Entity\SignupTournament
         */
        $signUp1 = clone $signUp;

        $signUp0->method('getFormula')->willReturn($signUp0Formula);
        $signUp1->method('getFormula')->willReturn($signUp1Formula);

        $result = $fightService->getHighestFormula($signUp0, $signUp1);

        $this->assertEquals($result, $expected);
    }

    public function additionProvider()
    {
        return [
            ['A', 'A', 'A'],
            ['A', 'B', 'A'],
            ['A', 'C', 'A'],
            ['B', 'A', 'A'],
            ['B', 'B', 'B'],
            ['B', 'C', 'B'],
            ['C', 'A', 'A'],
            ['C', 'B', 'B'],
            ['C', 'C', 'C'],
        ];
    }
}
