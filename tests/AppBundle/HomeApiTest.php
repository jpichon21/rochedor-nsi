<?php
namespace Tests\AppBundle;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use AppBundle\Entity\Page;

class HomeApiTest extends WebTestCase
{

    const RETURNED_JSON = 'home_returned_json.json';
    const RETURNED_JSON_VERSION = 'home_returned_json_version.json';
    const PUT_HOME = 'home_put.json';
    const PUT_HOME_ERROR = 'home_put_error.json';

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

    public function testGetOneHome()
    {
        $this->resetDB();
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/home/fr');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testReturnedJsonGetOneHome()
    {
        $this->resetDB();
        $client = self::createClient();
        $crawler =$client->request('GET', '/api/home/en');
        $response = $client->getResponse()->getContent();
        $arrayResponse = json_decode($response, true);
        
        
        $this->assertTrue(
            $arrayResponse === $this->loadJson($this::RETURNED_JSON, true)
        );
    }

    public function testReturnedJsonGetOneVersionHome()
    {
        $client = self::createClient();
        $crawler =$client->request('GET', '/api/home/fr/1');
        $response = $client->getResponse()->getContent();
        $arrayResponse = json_decode($response, true);
        
        $this->assertTrue(
            $arrayResponse === $this->loadJson($this::RETURNED_JSON_VERSION, true)
        );
    }

    public function testGetOneVersionHome()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/home/fr/1');
        $response = $client->getResponse();
        $arrayResponse = json_decode($response, true);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetVersionHome()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/home/2/versions');
        $response = $client->getResponse();
        $arrayResponse = json_decode($response, true);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testPutHome()
    {
            $client = self::createClient();
            $crawler = $client->request(
                'PUT',
                '/api/home/2',
                array(),
                array(),
                array('CONTENT_TYPE' => 'application/json'),
                $this->loadJson($this::PUT_HOME)
            );

            $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    
    public function testBadPutHome()
    {
            $client = self::createClient();
            $crawler = $client->request(
                'PUT',
                '/api/home/4000000',
                array(),
                array(),
                array('CONTENT_TYPE' => 'application/json'),
                $this->loadJson($this::PUT_HOME_ERROR)
            );
            $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testReturnedJsonVersionLogHome()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/home/2/versions');
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

    public function testBadGetOneHome()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/home/ch');
        $response = $client->getResponse();
        $arrayResponse = json_decode($response, true);
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testBadGetVersionHome()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/home/200000/versions');
        $response = $client->getResponse();
        $arrayResponse = json_decode($response, true);
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}
