<?php

namespace Tests\AppBundle\Service;

use AppBundle\Entity\Fight;
use AppBundle\Entity\SignupTournament;
use AppBundle\Service\FightService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Tests\Builder\TournamentBuilder;
use Tests\Builder\UserBuilder;
use Tests\Database;
use Tests\DatabaseHelper;

class FightServiceIntegrationTest extends TestCase
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var UserBuilder
     */
    private $userBuilder;

    /**
     * @var TournamentBuilder
     */
    private $tournamentBuilder;

    /**
     * @var FightService
     */
    private $fightService;

    /**
     * @var DatabaseHelper
     */
    private $databaseHelper;

    public function setUp()
    {
        $kernel = new \AppKernel('test', true);
        $kernel->boot();

        $container = $kernel->getContainer();
        $this->em = $container->get('doctrine')->getManager();
        $this->fightService = new FightService($this->em);

        $this->userBuilder = new UserBuilder();
        $this->tournamentBuilder = new TournamentBuilder();

        $this->databaseHelper = new DatabaseHelper(new Database('test'));
        $this->databaseHelper->truncateAllTables();
    }

    /**
     * @test
     */
    public function createFightFromSignUps()
    {
        $user1 = $this->userBuilder
            ->withName('user1')
            ->withEmail('user1@mail.com')
            ->build();

        $user2 = $this->userBuilder
            ->withName('user2')
            ->withEmail('user2@mail.com')
            ->build();

        $tournament = $this->tournamentBuilder
            ->build();

        $signUp1 = new SignupTournament($user1, $tournament);
        $signUp1->setWeight(69);
        $signUp1->setFormula('A');
        $signUp1->setDiscipline('boks');
        $signUp2 = new SignupTournament($user2, $tournament);
        $signUp2->setWeight(69);
        $signUp2->setFormula('A');
        $signUp2->setDiscipline('boks');

        $this->em->persist($user1);
        $this->em->persist($user2);
        $this->em->persist($tournament);
        $this->em->persist($signUp1);
        $this->em->persist($signUp2);
        $this->em->flush();

        $this->fightService->createFightFromSignUps($signUp1, $signUp2);

        $fight = $this->em->getRepository(Fight::class)
            ->find(1);
        $this->em->refresh($fight);

        $usersFight = $fight->getUsersFight();

        $this->assertNotEmpty($fight);
        $this->assertEquals(1, $usersFight[0]->getId());
        $this->assertTrue($usersFight[0]->isRedCorner());
        $this->assertEquals(2, $usersFight[1]->getId());
    }
}
