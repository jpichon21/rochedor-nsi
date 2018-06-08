<?php
namespace Tests\AppBundle;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use AppBundle\Entity\Speaker;

class SpeakerApiTest extends WebTestCase
{
    //test200
    public function testGetAllSpeaker()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/speaker');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetOneSpeaker()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/speaker/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetOneVersionSpeaker()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/speaker/1/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testVersionSpeaker()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/speaker/1/versions');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testReverseSpeaker()
    {
        $client = self::createClient();
        $crawler = $client->request('PUT', '/api/speaker/1/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
