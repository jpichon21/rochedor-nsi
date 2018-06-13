<?php
namespace Tests\AppBundle;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use AppBundle\Entity\Speaker;

class SpeakerApiTest extends WebTestCase
{
    const POST_SPEAKER = 'speaker_post.json';
    const PUT_SPEAKER = 'speaker_put.json';
    const PUT_SPEAKER_NOT_FOUND = 'speaker_put_not_found.json';
    const RETURNED_JSON = 'speaker_returned_json.json';
    const RETURNED_JSON_LIST = 'speaker_returned_json_list.json';
    const RETURNED_JSON_VERSION = 'speaker_returned_json_version.json';
    const RETURNED_JSON_POSITION = 'speaker_returned_json_position.json';

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
        exec("mysql -u ".$database_user." ".$database_name." -h '".$database_host."' < lrdo-test.sql");
    }

    //test GET
    public function testGetAllSpeaker()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/speaker');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetOneSpeaker()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/speaker/5');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetOneVersionSpeaker()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/speaker/147/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testVersionSpeaker()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/speaker/5/versions');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    
    public function testSetPosSpeaker()
    {
        $client = self::createClient();
        $crawler = $client->request('PUT', '/api/speaker/5/position/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testPutSpeaker()
    {
        $client = self::createClient();
        $crawler = $client->request(
            'PUT',
            '/api/speaker/5',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            $this->loadJson($this::PUT_SPEAKER)
        );
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    // test create
    public function testCreateSpeaker()
    {
        $client = self::createClient();
        $crawler = $client->request(
            'POST',
            '/api/speaker',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            $this->loadJson($this::POST_SPEAKER)
        );
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->resetDB();
    }

    // test not found
    
    public function testDeleteNotFoundSpeaker()
    {
        $client = self::createClient();
        $crawler = $client->request('DELETE', '/api/speaker/5000');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testDeleteSpeaker()
    {
        $client = self::createClient();
        $crawler = $client->request('DELETE', '/api/speaker/1');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->resetDB();
    }

    public function testPutNotFoundSpeaker()
    {
        $client = self::createClient();
        $crawler = $client->request(
            'PUT',
            '/api/speaker/5000',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            $this->loadJson($this::PUT_SPEAKER_NOT_FOUND)
        );
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testVersionNotFoundSpeaker()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/speaker/5000/versions');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    //test returned Json
    public function testReturnedPagesOnList()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/speaker');

        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"' // optional message shown on failure
        );
    }

    public function testReturnedOneSpeaker()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/speaker/5');

        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"' // optional message shown on failure
        );
    }

    public function testReturnedOneVersionSpeaker()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/speaker/5/1');

        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"' // optional message shown on failure
        );
    }
    
    public function testReturnedVersionSpeaker()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/speaker/5/versions');

        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"' // optional message shown on failure
        );
    }

    public function testReturnedSetPosSpeaker()
    {
        $client = self::createClient();
        $crawler = $client->request('PUT', '/api/speaker/5/position/1');

        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"' // optional message shown on failure
        );
    }

    public function testReturnedJsonSpeaker()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/speaker/6');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = $client->getResponse();
        $response = $response->getContent();
        $arrayResponse = json_decode($response, true);
        
        $this->assertTrue(
            $arrayResponse === $this->loadJson($this::RETURNED_JSON, true)
        );
    }

    public function testReturnedJsonListSpeaker()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/speaker');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = $client->getResponse();
        $response = $response->getContent();
        $arrayResponse = json_decode($response, true);
        
        $this->assertTrue(
            $arrayResponse === $this->loadJson($this::RETURNED_JSON_LIST, true)
        );
    }

    public function testReturnedJsonVersionSpeaker()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/speaker/5/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = $client->getResponse();
        $response = $response->getContent();
        $arrayResponse = json_decode($response, true);
        
        $this->assertTrue(
            $arrayResponse === $this->loadJson($this::RETURNED_JSON_VERSION, true)
        );
    }

    public function testReturnedJsonSetPosSpeaker()
    {
        $this->resetDB();
        $client = self::createClient();
        $crawler = $client->request('PUT', '/api/speaker/5/position/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = $client->getResponse();
        $response = $response->getContent();
        $arrayResponse = json_decode($response, true);
        
        $this->assertTrue(
            $arrayResponse === $this->loadJson($this::RETURNED_JSON_POSITION, true)
        );
    }

    public function testReturnedJsonVersionLogSpeaker()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/speaker/6/versions');
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
