<?php
namespace Tests\AppBundle;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use AppBundle\Entity\Page;

class PageApiTest extends WebTestCase
{
    // test 200
    public function testCreateOnePages()
    {
            $client = self::createClient();
            $crawler = $client->request(
                'POST',
                '/api/pages',
                array(),
                array(),
                array('CONTENT_TYPE' => 'application/json'),
                '{
                "title": "Mon test",
                "sub_title": "",
                "description": "",
                "content": [],
                "background": null,
                "locale": "fr",
                "parent": null,
                "children": [],
                "updated": "2018-05-31T10:06:35+08:00"
            }'
            );
            $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testBadCreateOnePages()
    {
            $client = self::createClient();
            $crawler = $client->request(
                'POST',
                '/api/pages',
                array(),
                array(),
                array('CONTENT_TYPE' => 'application/json'),
                '{
                "title": "Mon test",
                "sub_ti
                "description": "",
                "content": [],
                "background": null,
                "locale": "fr",
                "parent": null,
                "children": [],
                "updated": "2018-05-31T10:06:35+08:00"
            }'
            );
            $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }


    // // status 200/201 test
    public function testGetAllPages()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/pages');
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

    public function testPutPages()
    {
            $client = self::createClient();
            $crawler = $client->request(
                'PUT',
                '/api/pages/5',
                array(),
                array(),
                array('CONTENT_TYPE' => 'application/json'),
                '{
                "title": "Mon test",
                "sub_title": "",
                "description": "",
                "content": [],
                "background": null,
                "locale": "fr",
                "parent": null,
                "children": []
            }'
            );
            $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testRevertPages()
    {
        $client = self::createClient();
        $crawler = $client->request('PUT', '/api/pages/5/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testDeletePages()
    {
        $client = self::createClient();
        $crawler = $client->request('DELETE', '/api/pages/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }


    // test error 404

    public function testErrorDeletePages()
    {
        $client = self::createClient();
        $crawler = $client->request('DELETE', '/api/pages/1');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testErrorPutPages()
    {
            $client = self::createClient();
            $crawler = $client->request(
                'PUT',
                '/api/pages/1000000',
                array(),
                array(),
                array('CONTENT_TYPE' => 'application/json'),
                '{
                "title": "Mon test",
                "sub_title": "",
                "description": "",
                "content": [],
                "background": null,
                "locale": "fr",
                "parent": null,
                "children": []
            }'
            );
            $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testErrorShowPages()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/pages/10000');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testErrorChildPages()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/pages/10000/children');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    // test Returned Content

    public function testReturnedPagesOnList()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/pages');

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

    public function testReturnedPagesOnShowChild()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/pages/2/Children');

        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"' // optional message shown on failure
        );
    }
}
