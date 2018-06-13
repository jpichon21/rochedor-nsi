<?php
namespace Tests\AppBundle;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use AppBundle\Entity\Page;

class PageApiTest extends WebTestCase
{

    const NEW_PAGE = 'page_new.json';
    const NEW_PAGE_BAD = 'page_new_bad.json';
    const PUT_PAGE = 'page_put.json';
    const PUT_PAGE_ERROR = 'page_put_error.json';
    const RETURNED_JSON_LIST = 'page_returned_json_list.json';
    const RETURNED_JSON_VERSION = 'page_returned_json_version.json';
    const RETURNED_JSON = 'page_returned_json.json';
    const RETURNED_JSON_TRANSLATIONS = 'page_returned_json_translations.json';

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

    public function testCreateOnePage()
    {
        $this->resetDB();
        $client = self::createClient();
        $crawler = $client->request(
            'POST',
            '/api/pages',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            $this->loadJson($this::NEW_PAGE)
        );
            $this->resetDB();
            $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
        
    public function testBadCreateOnePage()
    {
        $client = self::createClient();
        $crawler = $client->request(
            'POST',
            '/api/pages',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            $this->loadJson($this::NEW_PAGE_BAD)
        );
            $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }
        
        
        // // status 200/201 test
    public function testGetAllPages()
    {
        $client = self::createClient();
        $crawler = $client->request(
            'GET',
            '/api/pages',
            array('locale' => 'fr'),
            array(),
            array()
        );
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
        
    public function testGetOnePages()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/pages/5');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
        
    public function testGetChildPages()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/pages/5');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
        
    public function testPutPage()
    {
        $client = self::createClient();
        $crawler = $client->request(
            'PUT',
            '/api/pages/2',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            $this->loadJson($this::PUT_PAGE)
        );
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testDeletePage()
    {
        $client = self::createClient();
        $crawler = $client->request('DELETE', '/api/pages/2');
        $this->resetDB();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }


    public function testErrorDeletePage()
    {
        $client = self::createClient();
        $crawler = $client->request('DELETE', '/api/pages/100000');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testErrorPutPage()
    {
            $client = self::createClient();
            $crawler = $client->request(
                'PUT',
                '/api/pages/1000000',
                array(),
                array(),
                array('CONTENT_TYPE' => 'application/json'),
                $this->loadJson($this::PUT_PAGE_ERROR)
            );
            $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testErrorShowPages()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/pages/10000');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testErrorBrotherPages()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/pages/10000/brother');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    // test Returned Content

    public function testReturnedPagesOnList()
    {
        $client = self::createClient();
        $crawler = $client->request(
            'GET',
            '/api/pages',
            array('locale' => 'en'),
            array(),
            array()
        );

        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"' // optional message shown on failure
        );
    }

    public function testReturnedPagesOnShow()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/pages/2');

        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"' // optional message shown on failure
        );
    }

    public function testReturnedPagesOnRevert()
    {
        $client = self::createClient();
        $crawler = $client->request('PUT', '/api/pages/2/1');

        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"' // optional message shown on failure
        );
    }

    public function testReturnedPagesOnShowBrother()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/pages/2/brother');

        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"' // optional message shown on failure
        );
    }

    public function testReturnedJsonPage()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/pages/19');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = $client->getResponse();
        $response = $response->getContent();
        $arrayResponse = json_decode($response, true);

        $this->assertTrue(
            $arrayResponse === $this->loadJson($this::RETURNED_JSON, true)
        );
    }

    public function testReturnedJsonListPage()
    {
        $this->resetDB();
        $client = self::createClient();
        $crawler = $client->request(
            "GET",
            "/api/pages",
            array("locale" => "it"),
            array(),
            array()
        );
        $response = $client->getResponse();
        $response = $response->getContent();
        $arrayResponse = json_decode($response, true);
        $this->assertTrue(
            $arrayResponse === $this->loadJson($this::RETURNED_JSON_LIST, true)
        );
    }

    public function testReturnedJsonVersionPage()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/pages/137/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = $client->getResponse();
        $response = $response->getContent();
        $arrayResponse = json_decode($response, true);
        
        $this->assertTrue(
            $arrayResponse === $this->loadJson($this::RETURNED_JSON_VERSION, true)
        );
    }

    public function testReturnedJsonGetTranslationsPage()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/pages/137/translations');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = $client->getResponse();
        $response = $response->getContent();
        $arrayResponse = json_decode($response, true);
        $this->assertTrue(
            $arrayResponse === $this->loadJson($this::RETURNED_JSON_TRANSLATIONS, true)
        );
    }

    public function testReturnedJsonVersionLogPage()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/pages/2/versions');
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
