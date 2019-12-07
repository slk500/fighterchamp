<?php


namespace Tests\AppBundle\Controller\Api;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ClubControllerTest extends WebTestCase
{
    /**
     * @test
     * @covers \AppBundle\Controller\Api\ClubController::list
     */
    public function list()
    {
        $client = static::createClient();

        $client->request('GET', 'api/clubs', [], [], [
            'HTTP_X-Requested-With' => 'XMLHttpRequest',
        ]);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}