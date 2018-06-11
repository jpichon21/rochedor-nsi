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
                '/api/pages/2',
                array(),
                array(),
                array('CONTENT_TYPE' => 'application/json'),
                '{
                "title": "Mon test"
            }'
            );
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
        $crawler = $client->request('DELETE', '/api/pages/100000');
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

    public function testReturnedJsonPage()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/pages/18');
        $response = $client->getResponse();
        $response = $response->getContent();
        $arrayResponse = json_decode($response, true);
        $speakerTest = [
            "id" => 18,
            "title" => "Mon test",
            "sub_title" => "",
            "description" => "",
            "content" => [],
            "background" => null,
            "locale" => "fr",
            "parent" => null,
            "children" => [],
            "routes" => [
                0 => [
                "path" => "/",
                "host" => "",
                "schemes" => [],
                "methods" => [],
                "defaults" =>[
                    "_content_id" => "AppBundle\Entity\Page:18"
                ],
                "requirements" => [],
                "options" => [],
                "condition" => "",
                "compiled" => null,
                "id" => 23,
                "content" => null,
                "static_prefix" => "/mon-test",
                "variable_pattern" => null,
                "need_recompile" => false,
                "name" => "mon-test",
                "position" => 0,
                ]
            ],
            "updated" => "2018-06-08T18:18:00+08:00",
            "url" => "mon-test",
            "parent_id" => null
        ];
        $this->assertTrue(
            $arrayResponse === $speakerTest
        );
    }

    public function testReturnedJsonListPage()
    {
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

        $speakerListTest = [
        1 => [
            "id" => 17,
            "title" => "Altoparlanti",
            "sub_title" => "elenco dei nostri relatori",
            "description" => "Meta-desc",
            "content" =>[
            "intro" => "",
            "sections" =>[
                0 => [
                "title" => "",
                "body" => "<p></p>\n",
                "slides" =>[
                    0 => [
                    "layout" => "1-1-2",
                    "images" =>[
                        0 =>[
                        "type" => "",
                        "url" => "",
                        "alt" => "",
                        "video" => "",
                        ],
                        1 => [
                        "type" => "",
                        "url" => "",
                        "alt" => "",
                        "video" => "",
                        ],
                        2 => [
                        "type" => "",
                        "url" => "",
                        "alt" => "",
                        "video" => "",
                        ],
                        3 => [
                        "type" => "",
                        "url" => "",
                        "alt" => "",
                        "video" => "",
                        ]
                    ]
                    ]
                ]
                ]
            ]
                        ],
            "background" => null,
            "locale" => "it",
            "parent" => [
            "id" => 13,
            "title" => "Intervenants",
            "sub_title" => "Liste de nos intervenants",
            "description" => "meta-descr",
            "content" => [
                "intro" => "",
                "sections" => [
                0 => [
                    "title" => "",
                    "body" => "<p></p>\n",
                    "slides" => [
                    0 => [
                        "layout" => "1-1-2",
                        "images" => [
                        0 => [
                            "type" => "",
                            "url" => "",
                            "alt" => "",
                            "video" => "",
                        ],
                        1 => [
                            "type" => "",
                            "url" => "",
                            "alt" => "",
                            "video" => "",
                        ],
                        2 => [
                            "type" => "",
                            "url" => "",
                            "alt" => "",
                            "video" => "",
                        ],
                        3 => [
                            "type" => "",
                            "url" => "",
                            "alt" => "",
                            "video" => "",
                        ]
                        ]
                    ]
                    ]
                ]
                ]
                        ],
            "background" => null,
            "locale" => "fr",
            "parent" => null,
            "children" => [
                0 => [
                "id" => 14,
                "title" => "Speaker",
                "sub_title" => "List of our speakers",
                "description" => "meta-desc",
                "content" => [
                    "intro" => "",
                    "sections" => [
                    0 => [
                        "title" => "",
                        "body" => "<p></p>\n",
                        "slides" => [
                        0 => [
                            "layout" => "1-1-2",
                            "images" => [
                            0 => [
                                "type" => "",
                                "url" => "",
                                "alt" => "",
                                "video" => "",
                            ],
                            1 => [
                                "type" => "",
                                "url" => "",
                                "alt" => "",
                                "video" => "",
                            ],
                            2 => [
                                "type" => "",
                                "url" => "",
                                "alt" => "",
                                "video" => "",
                            ],
                            3 => [
                                "type" => "",
                                "url" => "",
                                "alt" => "",
                                "video" => "",
                            ]
                            ]
                        ]
                        ]
                    ]
                    ]
                            ],
                "background" => null,
                "locale" => "en",
                "parent" => null,
                "children" => [],
                "routes" => [
                    0 => [
                    "path" => "/",
                    "host" => "",
                    "schemes" => [],
                    "methods" => [],
                    "defaults" => [
                        "_content_id" => "AppBundle\Entity\Page:14"
                    ],
                    "requirements" => [],
                    "options" => [],
                    "condition" => "",
                    "compiled" => null,
                    "id" => 19,
                    "content" => null,
                    "static_prefix" => "/speakers",
                    "variable_pattern" => null,
                    "need_recompile" => false,
                    "name" => "speakers",
                    "position" => 0,
                    ]
                ],
                "updated" => "2018-06-08T08:39:33+08:00",
                "url" => null,
                "parent_id" => null,
                ],
                1 => [
                "id" => 15,
                "title" => "Altavoces",
                "sub_title" => "lista de nuestros altavoces",
                "description" => "meta-desc",
                "content" => [
                    "intro" => "",
                    "sections" => [
                    0 => [
                        "title" => "",
                        "body" => "<p></p>\n",
                        "slides" =>[
                        0 => [
                            "layout" => "1-1-2",
                            "images" => [
                            0 => [
                                "type" => "",
                                "url" => "",
                                "alt" => "",
                                "video" => "",
                            ],
                            1 => [
                                "type" => "",
                                "url" => "",
                                "alt" => "",
                                "video" => "",
                            ],
                            2 => [
                                "type" => "",
                                "url" => "",
                                "alt" => "",
                                "video" => "",
                            ],
                            3 => [
                                "type" => "",
                                "url" => "",
                                "alt" => "",
                                "video" => "",
                            ]
                            ]
                        ]
                        ]
                    ]
                    ]
                            ],
                "background" => null,
                "locale" => "es",
                "parent" => null,
                "children" => [],
                "routes" => [
                    0 => [
                    "path" => "/",
                    "host" => "",
                    "schemes" => [],
                    "methods" => [],
                    "defaults" => [
                        "_content_id" => "AppBundle\Entity\Page:15"
                    ],
                    "requirements" => [],
                    "options" => [],
                    "condition" => "",
                    "compiled" => null,
                    "id" => 20,
                    "content" => null,
                    "static_prefix" => "/altavoces",
                    "variable_pattern" => null,
                    "need_recompile" => false,
                    "name" => "altavoces",
                    "position" => 0,
                    ]
                ],
                "updated" => "2018-06-08T08:40:55+08:00",
                "url" => null,
                "parent_id" => null,
                ],
                2 => [
                "id" => 16,
                "title" => "Lautsprecher",
                "sub_title" => "Liste unserer Referenten",
                "description" => "meta-desc",
                "content" => [
                    "intro" => "",
                    "sections" => [
                    0 => [
                        "title" => "",
                        "body" => "<p></p>\n",
                        "slides" => [
                        0 =>  [
                            "layout" => "1-1-2",
                            "images" =>[
                            0 => [
                                "type" => "",
                                "url" => "",
                                "alt" => "",
                                "video" => "",
                            ],
                            1 =>  [
                                "type" => "",
                                "url" => "",
                                "alt" => "",
                                "video" => "",
                            ],
                            2 => [
                                "type" => "",
                                "url" => "",
                                "alt" => "",
                                "video" => "",
                            ],
                            3 => [
                                "type" => "",
                                "url" => "",
                                "alt" => "",
                                "video" => "",
                            ]
                            ]
                        ]
                        ]
                    ]
                    ]
                            ],
                "background" => null,
                "locale" => "de",
                "parent" => null,
                "children" => [],
                "routes" => [
                    0 => [
                    "path" => "/",
                    "host" => "",
                    "schemes" => [],
                    "methods" => [],
                    "defaults" => [
                        "_content_id" => "AppBundle\Entity\Page:16"
                    ],
                    "requirements" => [],
                    "options" => [],
                    "condition" => "",
                    "compiled" => null,
                    "id" => 21,
                    "content" => null,
                    "static_prefix" => "/lautsprecher",
                    "variable_pattern" => null,
                    "need_recompile" => false,
                    "name" => "lautsprecher",
                    "position" => 0,
                    ],
                ],
                "updated" => "2018-06-08T08:42:02+08:00",
                "url" => null,
                "parent_id" => null,
                ],
                3 => null,
            ],
            "routes" =>  [
                0 =>  [
                "path" => "/",
                "host" => "",
                "schemes" => [],
                "methods" => [],
                "defaults" => [
                    "_content_id" => "AppBundle\Entity\Page:13"
                ],
                "requirements" => [],
                "options" => [],
                "condition" => "",
                "compiled" => null,
                "id" => 18,
                "content" => null,
                "static_prefix" => "/intervenants",
                "variable_pattern" => null,
                "need_recompile" => false,
                "name" => "intervenants",
                "position" => 0,
                ]
            ],
            "updated" => "2018-06-08T08:38:51+08:00",
            "url" => null,
            "parent_id" => null,
            ],
            "children" => [],
            "routes" =>[
            0 => [
                "path" => "/",
                "host" => "",
                "schemes" => [],
                "methods" => [],
                "defaults" => [
                "_content_id" => "AppBundle\Entity\Page:17"
                ],
                "requirements" => [],
                "options" => [],
                "condition" => "",
                "compiled" => null,
                "id" => 22,
                "content" => null,
                "static_prefix" => "/altoparlanti",
                "variable_pattern" => null,
                "need_recompile" => false,
                "name" => "altoparlanti",
                "position" => 0,
            ]
            ],
            "updated" => "2018-06-08T08:42:48+08:00",
            "url" => null,
            "parent_id" => null,
        ]
        ];

        $this->assertTrue(
            $arrayResponse === $speakerListTest
        );
    }

    public function testReturnedJsonVersionPage()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/pages/18/1');
        $response = $client->getResponse();
        $response = $response->getContent();
        $arrayResponse = json_decode($response, true);
        $speakerVersionTest = [
                "id" => 18,
                "title" => "Mon test",
                "sub_title" => "",
                "description" => "",
                "content" => [],
                "background" => null,
                "locale" => "fr",
                "parent" => null,
                "children" => [],
                "routes" =>[
                    0 => [
                    "path" => "/",
                    "host" => "",
                    "schemes" => [],
                    "methods" => [],
                    "defaults" =>  [
                        "_content_id" => "AppBundle\Entity\Page:18"
                    ],
                    "requirements" => [],
                    "options" => [],
                    "condition" => "",
                    "compiled" => null,
                    "id" => 23,
                    "content" => null,
                    "static_prefix" => "/mon-test",
                    "variable_pattern" => null,
                    "need_recompile" => false,
                    "name" => "mon-test",
                    "position" => 0,
                    ]
                ],
                "updated" => "2018-06-08T18:18:00+08:00",
                "url" => "mon-test",
                "parent_id" => null,
            ];
        $this->assertTrue(
            $arrayResponse === $speakerVersionTest
        );
    }

    public function testReturnedJsonGetTranslationPage()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/pages/13/translation');
        $response = $client->getResponse();
        $response = $response->getContent();
        $arrayResponse = json_decode($response, true);
        $speakerVersionTest = [
        0 => [
            "id" => 14,
            "title" => "Speaker",
            "sub_title" => "List of our speakers",
            "description" => "meta-desc",
            "content" =>  [
            "intro" => "",
            "sections" =>  [
                0 =>  [
                "title" => "",
                "body" => "<p></p>\n",
                "slides" =>  [
                    0 =>  [
                    "layout" => "1-1-2",
                    "images" =>  [
                        0 =>  [
                        "type" => "",
                        "url" => "",
                        "alt" => "",
                        "video" => "",
                        ],
                        1 =>  [
                        "type" => "",
                        "url" => "",
                        "alt" => "",
                        "video" => "",
                        ],
                        2 =>  [
                        "type" => "",
                        "url" => "",
                        "alt" => "",
                        "video" => "",
                        ],
                        3 =>  [
                        "type" => "",
                        "url" => "",
                        "alt" => "",
                        "video" => "",
                        ]
                    ]
                    ]
                ]
                ]
            ]
                        ],
            "background" => null,
            "locale" => "en",
            "parent" => [
            "id" => 13,
            "title" => "Intervenants",
            "sub_title" => "Liste de nos intervenants",
            "description" => "meta-descr",
            "content" =>  [
                "intro" => "",
                "sections" =>  [
                0 =>  [
                    "title" => "",
                    "body" => "<p></p>\n",
                    "slides" =>  [
                    0 =>  [
                        "layout" => "1-1-2",
                        "images" =>  [
                        0 =>  [
                            "type" => "",
                            "url" => "",
                            "alt" => "",
                            "video" => "",
                        ],
                        1 =>  [
                            "type" => "",
                            "url" => "",
                            "alt" => "",
                            "video" => "",
                        ],
                        2 =>  [
                            "type" => "",
                            "url" => "",
                            "alt" => "",
                            "video" => "",
                        ],
                        3 =>  [
                            "type" => "",
                            "url" => "",
                            "alt" => "",
                            "video" => "",
                        ]
                        ]
                    ]
                    ]
                ]
                ]
                        ],
            "background" => null,
            "locale" => "fr",
            "parent" => null,
            "children" => null,
            "routes" =>  [
                0 => [
                "path" => "/",
                "host" => "",
                "schemes" => [],
                "methods" => [],
                "defaults" =>  [
                    "_content_id" => "AppBundle\Entity\Page:13"
                ],
                "requirements" => [],
                "options" => [],
                "condition" => "",
                "compiled" => null,
                "id" => 18,
                "content" => null,
                "static_prefix" => "/intervenants",
                "variable_pattern" => null,
                "need_recompile" => false,
                "name" => "intervenants",
                "position" => 0,
                ]
            ],
            "updated" => "2018-06-08T08:38:51+08:00",
            "url" => null,
            "parent_id" => null,
            ],
            "children" => [],
            "routes" =>  [
            0 => [
                "path" => "/",
                "host" => "",
                "schemes" => [],
                "methods" => [],
                "defaults" =>  [
                "_content_id" => "AppBundle\Entity\Page:14"
                ],
                "requirements" => [],
                "options" => [],
                "condition" => "",
                "compiled" => null,
                "id" => 19,
                "content" => null,
                "static_prefix" => "/speakers",
                "variable_pattern" => null,
                "need_recompile" => false,
                "name" => "speakers",
                "position" => 0,
            ]
            ],
            "updated" => "2018-06-08T08:39:33+08:00",
            "url" => null,
            "parent_id" => null,
        ],
        1 => [
            "id" => 15,
            "title" => "Altavoces",
            "sub_title" => "lista de nuestros altavoces",
            "description" => "meta-desc",
            "content" =>  [
            "intro" => "",
            "sections" =>  [
                0 =>  [
                "title" => "",
                "body" => "<p></p>\n",
                "slides" =>  [
                    0 =>  [
                    "layout" => "1-1-2",
                    "images" =>  [
                        0 =>  [
                        "type" => "",
                        "url" => "",
                        "alt" => "",
                        "video" => "",
                        ],
                        1 =>  [
                        "type" => "",
                        "url" => "",
                        "alt" => "",
                        "video" => "",
                        ],
                        2 =>  [
                        "type" => "",
                        "url" => "",
                        "alt" => "",
                        "video" => "",
                        ],
                        3 =>  [
                        "type" => "",
                        "url" => "",
                        "alt" => "",
                        "video" => "",
                        ]
                    ]
                    ]
                ]
                ]
            ]
                        ],
            "background" => null,
            "locale" => "es",
            "parent" => [
            "id" => 13,
            "title" => "Intervenants",
            "sub_title" => "Liste de nos intervenants",
            "description" => "meta-descr",
            "content" =>  [
                "intro" => "",
                "sections" =>  [
                0 =>  [
                    "title" => "",
                    "body" => "<p></p>\n",
                    "slides" =>  [
                    0 =>  [
                        "layout" => "1-1-2",
                        "images" =>  [
                        0 =>  [
                            "type" => "",
                            "url" => "",
                            "alt" => "",
                            "video" => "",
                        ],
                        1 =>  [
                            "type" => "",
                            "url" => "",
                            "alt" => "",
                            "video" => "",
                        ],
                        2 =>  [
                            "type" => "",
                            "url" => "",
                            "alt" => "",
                            "video" => "",
                        ],
                        3 =>  [
                            "type" => "",
                            "url" => "",
                            "alt" => "",
                            "video" => "",
                        ],
                        ]
                    ]
                    ]
                ]
                ]
                    ],
            "background" => null,
            "locale" => "fr",
            "parent" => null,
            "children" => null,
            "routes" =>  [
                0 => [
                "path" => "/",
                "host" => "",
                "schemes" => [],
                "methods" => [],
                "defaults" =>  [
                    "_content_id" => "AppBundle\Entity\Page:13"
                ],
                "requirements" => [],
                "options" => [],
                "condition" => "",
                "compiled" => null,
                "id" => 18,
                "content" => null,
                "static_prefix" => "/intervenants",
                "variable_pattern" => null,
                "need_recompile" => false,
                "name" => "intervenants",
                "position" => 0,
                ]
            ],
            "updated" => "2018-06-08T08:38:51+08:00",
            "url" => null,
            "parent_id" => null,
            ],
            "children" => [],
            "routes" =>  [
            0 => [
                "path" => "/",
                "host" => "",
                "schemes" => [],
                "methods" => [],
                "defaults" =>  [
                "_content_id" => "AppBundle\Entity\Page:15"
                ],
                "requirements" => [],
                "options" => [],
                "condition" => "",
                "compiled" => null,
                "id" => 20,
                "content" => null,
                "static_prefix" => "/altavoces",
                "variable_pattern" => null,
                "need_recompile" => false,
                "name" => "altavoces",
                "position" => 0,
            ]
            ],
            "updated" => "2018-06-08T08:40:55+08:00",
            "url" => null,
            "parent_id" => null,
        ],
        2 => [
            "id" => 16,
            "title" => "Lautsprecher",
            "sub_title" => "Liste unserer Referenten",
            "description" => "meta-desc",
            "content" =>  [
            "intro" => "",
            "sections" =>  [
                0 =>  [
                "title" => "",
                "body" => "<p></p>\n",
                "slides" =>  [
                    0 =>  [
                    "layout" => "1-1-2",
                    "images" =>  [
                        0 =>  [
                        "type" => "",
                        "url" => "",
                        "alt" => "",
                        "video" => "",
                        ],
                        1 =>  [
                        "type" => "",
                        "url" => "",
                        "alt" => "",
                        "video" => "",
                        ],
                        2 =>  [
                        "type" => "",
                        "url" => "",
                        "alt" => "",
                        "video" => "",
                        ],
                        3 =>  [
                        "type" => "",
                        "url" => "",
                        "alt" => "",
                        "video" => "",
                        ]
                    ]
                    ]
                ]
                ]
            ]
                        ],
            "background" => null,
            "locale" => "de",
            "parent" => [
            "id" => 13,
            "title" => "Intervenants",
            "sub_title" => "Liste de nos intervenants",
            "description" => "meta-descr",
            "content" =>  [
                "intro" => "",
                "sections" =>  [
                0 =>  [
                    "title" => "",
                    "body" => "<p></p>\n",
                    "slides" =>  [
                    0 =>  [
                        "layout" => "1-1-2",
                        "images" =>  [
                        0 =>  [
                            "type" => "",
                            "url" => "",
                            "alt" => "",
                            "video" => "",
                        ],
                        1 =>  [
                            "type" => "",
                            "url" => "",
                            "alt" => "",
                            "video" => "",
                        ],
                        2 =>  [
                            "type" => "",
                            "url" => "",
                            "alt" => "",
                            "video" => "",
                        ],
                        3 =>  [
                            "type" => "",
                            "url" => "",
                            "alt" => "",
                            "video" => "",
                        ]
                        ]
                    ]
                    ]
                ]
                ]
                        ],
            "background" => null,
            "locale" => "fr",
            "parent" => null,
            "children" => null,
            "routes" =>  [
                0 =>[
                "path" => "/",
                "host" => "",
                "schemes" => [],
                "methods" => [],
                "defaults" =>  [
                    "_content_id" => "AppBundle\Entity\Page:13"
                ],
                "requirements" => [],
                "options" => [],
                "condition" => "",
                "compiled" => null,
                "id" => 18,
                "content" => null,
                "static_prefix" => "/intervenants",
                "variable_pattern" => null,
                "need_recompile" => false,
                "name" => "intervenants",
                "position" => 0
                ]
            ],
            "updated" => "2018-06-08T08:38:51+08:00",
            "url" => null,
            "parent_id" => null,
            ],
            "children" => [],
            "routes" =>  [
            0 =>[
                "path" => "/",
                "host" => "",
                "schemes" => [],
                "methods" => [],
                "defaults" =>  [
                "_content_id" => "AppBundle\Entity\Page:16"
                ],
                "requirements" => [],
                "options" => [],
                "condition" => "",
                "compiled" => null,
                "id" => 21,
                "content" => null,
                "static_prefix" => "/lautsprecher",
                "variable_pattern" => null,
                "need_recompile" => false,
                "name" => "lautsprecher",
                "position" => 0,
            ]
            ],
            "updated" => "2018-06-08T08:42:02+08:00",
            "url" => null,
            "parent_id" => null,
        ],
        3 =>  [
            "id" => 17,
            "title" => "Altoparlanti",
            "sub_title" => "elenco dei nostri relatori",
            "description" => "Meta-desc",
            "content" =>  [
            "intro" => "",
            "sections" =>  [
                0 =>  [
                "title" => "",
                "body" => "<p></p>\n",
                "slides" =>  [
                    0 =>  [
                    "layout" => "1-1-2",
                    "images" =>  [
                        0 =>  [
                        "type" => "",
                        "url" => "",
                        "alt" => "",
                        "video" => "",
                        ],
                        1 =>  [
                        "type" => "",
                        "url" => "",
                        "alt" => "",
                        "video" => "",
                        ],
                        2 =>  [
                        "type" => "",
                        "url" => "",
                        "alt" => "",
                        "video" => "",
                        ],
                        3 =>  [
                        "type" => "",
                        "url" => "",
                        "alt" => "",
                        "video" => "",
                        ],
                    ]
                    ]
                ]
                ]
            ]
                    ],
            "background" => null,
            "locale" => "it",
            "parent" => [
            "id" => 13,
            "title" => "Intervenants",
            "sub_title" => "Liste de nos intervenants",
            "description" => "meta-descr",
            "content" =>  [
                "intro" => "",
                "sections" =>  [
                0 =>  [
                    "title" => "",
                    "body" => "<p></p>\n",
                    "slides" =>  [
                    0 =>  [
                        "layout" => "1-1-2",
                        "images" =>  [
                        0 =>  [
                            "type" => "",
                            "url" => "",
                            "alt" => "",
                            "video" => "",
                        ],
                        1 =>  [
                            "type" => "",
                            "url" => "",
                            "alt" => "",
                            "video" => "",
                        ],
                        2 =>  [
                            "type" => "",
                            "url" => "",
                            "alt" => "",
                            "video" => "",
                        ],
                        3 =>  [
                            "type" => "",
                            "url" => "",
                            "alt" => "",
                            "video" => "",
                        ],
                        ]
                    ]
                    ]
                ]
                ]
                    ],
            "background" => null,
            "locale" => "fr",
            "parent" => null,
            "children" => null,
            "routes" =>  [
                0 => [
                "path" => "/",
                "host" => "",
                "schemes" => [],
                "methods" => [],
                "defaults" =>  [
                    "_content_id" => "AppBundle\Entity\Page:13"
                ],
                "requirements" => [],
                "options" => [],
                "condition" => "",
                "compiled" => null,
                "id" => 18,
                "content" => null,
                "static_prefix" => "/intervenants",
                "variable_pattern" => null,
                "need_recompile" => false,
                "name" => "intervenants",
                "position" => 0,
                ]
            ],
            "updated" => "2018-06-08T08:38:51+08:00",
            "url" => null,
            "parent_id" => null,
            ],
            "children" => [],
            "routes" =>  [
            0 => [
                "path" => "/",
                "host" => "",
                "schemes" => [],
                "methods" => [],
                "defaults" =>  [
                "_content_id" => "AppBundle\Entity\Page:17"
                ],
                "requirements" => [],
                "options" => [],
                "condition" => "",
                "compiled" => null,
                "id" => 22,
                "content" => null,
                "static_prefix" => "/altoparlanti",
                "variable_pattern" => null,
                "need_recompile" => false,
                "name" => "altoparlanti",
                "position" => 0,
            ]
            ],
            "updated" => "2018-06-08T08:42:48+08:00",
            "url" => null,
            "parent_id" => null,
            ]
                ];

        $this->assertTrue(
            $arrayResponse === $speakerVersionTest
        );
    }

    public function testForbidenBrotherPage()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/pages/18/brother');
        $response = $client->getResponse();
        $response = $response->getContent();
        $arrayResponse = json_decode($response, true);
        $speakerVersionTest =  [
            "message" => "Page has no parent"
        ];
        $this->assertTrue(
            $arrayResponse === $speakerVersionTest
        );
    }

    public function testReturnedJsonBrotherPage()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/pages/19/brother');
        $response = $client->getResponse();
        $response = $response->getContent();
        $arrayResponse = json_decode($response, true);
        $speakerVersionTest = [
            0 =>  [
                "id" => 19,
                "title" => "Mon test 2",
                "sub_title" => "",
                "description" => "",
                "content" => [],
                "background" => null,
                "locale" => "fr",
                "parent" => null,
                "children" => null,
                "routes" => [],
                "updated" => "2018-06-08T18:26:24+08:00",
                "url" => null,
                "parent_id" => null,
            ]
        ];
        $this->assertTrue(
            $arrayResponse === $speakerVersionTest
        );
    }


    public function testReturnedJsonVersionLogPage()
    {
        $client = self::createClient();
        $crawler = $client->request('PUT', '/api/pages/18/versions');
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
