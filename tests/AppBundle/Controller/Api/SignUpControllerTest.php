<?php

namespace Tests\AppBundle\Controller\Api;

use AppBundle\DataFixtures\ORM\RulesetFixtures;
use AppBundle\Entity\SignupTournament;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\Builder\SignupTournamentBuilder;
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
    /**
     * @var SignupTournamentBuilder
     */
    private $signupTournamentBuilder;
    /**
     * @var \Symfony\Bundle\FrameworkBundle\KernelBrowser
     */
    private $client;


    public function setUp()
    {
        $kernel = new \AppKernel('test', true);
        $kernel->boot();

        $container = $kernel->getContainer();
        $this->em = $container->get('doctrine')->getManager();

        $this->userBuilder = new UserBuilder();
        $this->tournamentBuilder = new TournamentBuilder();
        $this->signupTournamentBuilder = new SignupTournamentBuilder();

        $this->client = static::createClient();

        $this->databaseHelper = new DatabaseHelper(new Database('test'));
        $this->databaseHelper->truncateAllTables();
    }
    
    /**
     * @test
     * @covers \AppBundle\Controller\Api\TournamentSignUpController::create
     */
    public function create()
    {
        $user = $this->userBuilder
            ->build();

        $tournament = $this->tournamentBuilder->build();

        $this->em->persist($user);
        $this->em->persist($tournament);
        $this->em->flush();

        $data = [
            'userId' => 1,
            'tournamentId' => 1,
            'formula' => 'A',
            'discipline' => 'Boks',
            'weight' => '70'
        ];

        $this->client->request(
            'POST',
            '/api/signups',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest'
            ],
            json_encode($data)
        );

        $signup = $this->em
            ->getRepository(SignupTournament::class)
            ->find(1);

        $this->assertNotEmpty($signup);

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @test
     * @covers \AppBundle\Controller\Api\TournamentSignUpController::update
     */
    public function update()
    {
        $user = $this->userBuilder
            ->build();

        $tournament = $this->tournamentBuilder->build();
        $signup = $this->signupTournamentBuilder
            ->withTournament($tournament)
            ->withUser($user)
            ->withFormula('A')
            ->withWeight('50')
            ->withDiscipline('Boks')
            ->build();

        $this->em->persist($user);
        $this->em->persist($tournament);
        $this->em->persist($signup);
        $this->em->flush();

        $rule = new RulesetFixtures($this->em);
        $rule->load($this->em);

        $formula = 'B';
        $weight = '60';
        $discipline = 'MMA';

        $data = [
            'formula' => $formula,
            'weight' => $weight,
            'discipline' => $discipline
        ];

        $this->client->request(
            'PATCH',
            '/api/signups/1',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest'
            ],
            json_encode($data)
        );

        $signup = $this->em
            ->getRepository(SignupTournament::class)
            ->find(1);

        $this->em->refresh($signup);

        $this->assertNotEmpty($signup);
        $this->assertEquals($formula, $signup->getFormula());
        $this->assertEquals($weight, $signup->getWeight());

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
