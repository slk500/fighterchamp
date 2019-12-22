<?php

namespace Tests\AppBundle\Controller\Api;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Client;
use Tests\Builder\UserBuilder;
use Tests\Database;
use Tests\DatabaseHelper;

class TokenControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var UserBuilder
     */
    private $userBuilder;

    /**
     * @var DatabaseHelper
     */
    private $databaseHelper;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->userBuilder = new UserBuilder();

        $kernel = new \AppKernel('test', true);
        $kernel->boot();
        $container = $kernel->getContainer();
        $this->em = $container->get('doctrine')->getManager();

        $this->databaseHelper = new DatabaseHelper(new Database('test'));
        $this->databaseHelper->truncateAllTables();

        $user = $this->userBuilder
            ->withName('admin')
            ->withPassword('password')
            ->build();

        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @covers \AppBundle\Controller\Api\TokenController
     */
    public function testPOSTCreateToken()
    {
        $this->client->request('POST','/api/tokens',[],[],[
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'password',
        ]);

        $response = $this->client->getResponse();

        $data = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('token', $data);

        $this->assertResponseStatusCodeSame(200);
    }

    public function testIsValid()
    {
        $em = $this->client->getContainer()->get('doctrine.orm.entity_manager');

        $user = $em->getRepository(User::class)->findOneBy(['name' => 'admin']);

        $isValid = $this->client->getContainer()->get('security.password_encoder')->isPasswordValid($user, 'password');

        $this->assertTrue($isValid);
    }

    /**
     * @test
     * @covers \AppBundle\Controller\Api\TokenController
     */
    public function isLogIn()
    {
        $token = $this->client->getContainer()
            ->get('lexik_jwt_authentication.encoder')
            ->encode(['userId' => 1]);

        $this->client->request('GET', 'api/users',[],[],[
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
            'HTTP_X-Requested-With' => 'XMLHttpRequest',
        ]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertJson($this->client->getResponse()->getContent());
    }

}
