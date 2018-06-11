<?php
namespace Tests\AppBundle;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use AppBundle\Entity\Page;

class NewsApiTest extends WebTestCase
{
    public function testGetAllNews()
    {
        $client = self::createClient();
        $crawler = $client->request(
            'GET',
            '/api/news',
            array('locale' => 'fr'),
            array(),
            array()
        );
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetOneNews()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/news/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetOneVersionNews()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/news/1/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testVersionNews()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/news/5/versions');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }


    public function testCreateOneNews()
    {
            $client = self::createClient();
            $crawler = $client->request(
                'POST',
                '/api/news',
                array(),
                array(),
                array('CONTENT_TYPE' => 'application/json'),
                '{
                "intro": "aea",
                "description": "azezr",
                "url": "lolilol.com",
                "start": "2018-06-07T00:09:36+08:00",
                "stop": "2018-06-07T00:47:36+08:00",
                "locale": "fr"
            }'
            );
            $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testPutOneNews()
    {
        $client = self::createClient();
        $crawler = $client->request(
            'PUT',
            '/api/news/1',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{
                "intro": "aea",
                "description": "azezr",
                "url": "lolilol.com",
                "start": "2018-06-07T00:09:36+08:00",
                "stop": "2018-06-07T00:47:36+08:00",
                "locale": "fr"
            }'
        );
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    
    // test not found
    
    public function testDeleteNotFoundNews()
    {
        $client = self::createClient();
        $crawler = $client->request('DELETE', '/api/news/5000');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testPutNotFoundNews()
    {
        $client = self::createClient();
        $crawler = $client->request(
            'PUT',
            '/api/news/5000',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{
                "intro": "aea",
                "description": "azezr",
                "url": "lolilol.com",
                "start": "2018-06-07T00:09:36+08:00",
                "stop": "2018-06-07T00:47:36+08:00",
                "locale": "fr"
            }'
        );
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testVersionNotFoundNews()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/news/5000/version');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    //test returned Json
    public function testReturnedNewsOnList()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/news');

        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"' // optional message shown on failure
        );
    }

    public function testReturnedOneNews()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/news/1');

        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"' // optional message shown on failure
        );
    }

    public function testReturnedOneVersionNews()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/news/1/1');

        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"' // optional message shown on failure
        );
    }
    
    public function testReturnedVersionNews()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/news/5/version');

        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"' // optional message shown on failure
        );
    }

    public function testReturnedJsonOneNews()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/news/2');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = $client->getResponse();
        $response = $response->getContent();
        $arrayResponse = json_decode($response, true);
        $newsTest = [
            "id" => 2,
            "intro" => "ikjubg",
            "description" => "hfvyu",
            "url" => "https://www.google.fr/",
            "start" => "2018-06-07T13:17:23+08:00",
            "stop" => "2018-06-07T13:17:23+08:00",
            "locale" => "en",
        ];

        $this->assertTrue(
            $arrayResponse === $newsTest
        );
    }

    public function testReturnedJsonListNews()
    {
        $client = self::createClient();
        $crawler = $client->request(
            "GET",
            "/api/news",
            array("locale" => "fr"),
            array(),
            array()
        );
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = $client->getResponse();
        $response = $response->getContent();
        $arrayResponse = json_decode($response, true);
        $newsTest = [
        ];
        // dump($response);
        // exit;
        $this->assertTrue(
            $arrayResponse === $newsTest
        );
    }
    
    public function testReturnedJsonVersionLogNews()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/news/1/versions');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = $client->getResponse();
        $response = $response->getContent();
        $arrayResponse = json_decode($response, true);
        if (empty($arrayResponse)) {
            $empty = true;
        } else {
            $empty = false;
        }
        $this->assertTrue(
            $empty === false
        );
    }
}
