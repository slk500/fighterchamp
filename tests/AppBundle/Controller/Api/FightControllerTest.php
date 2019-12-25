<?php

namespace Tests\AppBundle\Controller\Api;

use AppBundle\Entity\Fight;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\Builder\SignupTournamentBuilder;
use Tests\Builder\TournamentBuilder;
use Tests\Builder\UserBuilder;
use Tests\Database;
use Tests\DatabaseHelper;

class FightControllerTest extends WebTestCase
{
    /**
     * @var UserBuilder
     */
    private $userBuilder;
    /**
     * @var TournamentBuilder
     */
    private $tournamentBuilder;
    /**
     * @var DatabaseHelper
     */
    private $databaseHelper;
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager|object
     */
    private $em;
    /**
     * @var SignupTournamentBuilder
     */
    private $signupTournamentBuilder;

    public function setUp()
    {
        $kernel = new \AppKernel('test', true);
        $kernel->boot();

        $container = $kernel->getContainer();
        $this->em = $container->get('doctrine')->getManager();

        $this->userBuilder = new UserBuilder();
        $this->tournamentBuilder = new TournamentBuilder();
        $this->signupTournamentBuilder = new SignupTournamentBuilder();

        $this->databaseHelper = new DatabaseHelper(new Database('test'));
        $this->databaseHelper->truncateAllTables();
    }
    
    /**
     * @test
     * @covers \AppBundle\Controller\Api\FightController::create
     */
    public function create()
    {
        $user1 = $this->userBuilder
            ->build();

        $user2 = $this->userBuilder
            ->withEmail('user2@email.com')
            ->build();

        $tournament = $this->tournamentBuilder->build();
        $signup1 = $this->signupTournamentBuilder
            ->withTournament($tournament)
            ->withUser($user1)
            ->build();

        $signup2 = $this->signupTournamentBuilder
            ->withTournament($tournament)
            ->withUser($user2)
            ->build();

        $this->em->persist($user1);
        $this->em->persist($user2);
        $this->em->persist($tournament);
        $this->em->persist($signup1);
        $this->em->persist($signup2);
        $this->em->flush();

        $client = static::createClient();

        $data = [
            'signup_1' => 1,
            'signup_2' => 2
        ];

        $client->request('POST', 'api/fights', $data, [], [
            'HTTP_X-Requested-With' => 'XMLHttpRequest',
            'CONTENT_TYPE' => 'application/json',
        ]);

        $fight = $this->em
            ->getRepository(Fight::class)
            ->find(1);

        $this->assertNotEmpty($fight);

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }
}
