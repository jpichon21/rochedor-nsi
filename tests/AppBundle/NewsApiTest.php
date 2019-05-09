<?php
namespace Tests\AppBundle;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use AppBundle\Entity\Page;

class NewsApiTest extends WebTestCase
{
    const POST_NEWS = 'news_post.json';
    const PUT_NEWS = 'news_put.json';
    const RETURNED_JSON = 'news_returned_json.json';

    private function loadJson($jsonFile, $toArray = false)
    {
        $json = file_get_contents(__DIR__.'/../json_data/'.$jsonFile);
        return ($toArray) ? json_decode($json, true) : $json;
    }

    private function resetDB()
    {
        $client = static::createClient();

        $container = $client->getKernel()->getContainer();
        $database_user = $container->getParameter('database_user');
        $database_password = $container->getParameter('database_password');
        $database_name = $container->getParameter('database_name');
        $database_host = $container->getParameter('database_host');
        $database_host = $container->getParameter('database_host');
        $database_port = $container->getParameter('database_port');
        exec('export MYSQL_PWD='.$database_password);
        exec("mysql -u ".$database_user." ".$database_name." -h '".$database_host."' < ".__DIR__."/../lrdo-test.sql");
    }

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
                $this->loadJson($this::POST_NEWS)
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
            $this->loadJson($this::PUT_NEWS)
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
            $this->loadJson($this::PUT_NEWS)
        );
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testVersionNotFoundNews()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/news/5000/versions');
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
        $crawler = $client->request('GET', '/api/news/5/versions');

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

        $this->assertTrue(
            $arrayResponse === $this->loadJson($this::RETURNED_JSON, true)
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
