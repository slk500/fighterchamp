<?php

namespace Tests\AppBundle\Controller\Api;

use AppBundle\Entity\SignUpTournament;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\Builder\TournamentBuilder;
use Tests\Builder\UserBuilder;
use Tests\Database;
use Tests\DatabaseHelper;

class SignUpControllerTest extends WebTestCase
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

    public function setUp()
    {
        $kernel = new \AppKernel('test', true);
        $kernel->boot();

        $container = $kernel->getContainer();
        $this->em = $container->get('doctrine')->getManager();

        $this->userBuilder = new UserBuilder();
        $this->tournamentBuilder = new TournamentBuilder();

        $this->databaseHelper = new DatabaseHelper(new Database('test'));
        $this->databaseHelper->truncateAllTables();
    }
    
    /**
     * @test
     * @covers \AppBundle\Controller\Api\SignUpController::create
     */
    public function create()
    {
        $user = $this->userBuilder->build();
        $tournament = $this->tournamentBuilder->build();

        $this->em->persist($user);
        $this->em->persist($tournament);
        $this->em->flush();

        $client = static::createClient();

        $data = [
            'userId' => 1,
            'tournamentId' => 1,
            'formula' => 'boks',
            'weight' => '70'
        ];

        $client->request('POST', 'api/signups', $data, [], [
            'HTTP_X-Requested-With' => 'XMLHttpRequest',
            'CONTENT_TYPE' => 'application/json',
        ]);

        $signup = $this->em
            ->getRepository(SignUpTournament::class)
            ->find(1);

        $this->assertNotEmpty($signup);

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }
}
