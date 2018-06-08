<?php
namespace Tests\AppBundle;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use AppBundle\Entity\Speaker;

class SpeakerApiTest extends WebTestCase
{
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
        $crawler = $client->request('GET', '/api/speaker/5/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testVersionSpeaker()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/speaker/5/versions');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    
    // Test PUT
    public function testReverseSpeaker()
    {
        $client = self::createClient();
        $crawler = $client->request('PUT', '/api/speaker/5/1');
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
            '{
                    "name": "Raoul Fernando",
                    "title": {
                        "fr": "Maître de conférence",
                        "en": "Senior Lecturer",
                        "es": "Conferenciante senior",
                        "de": "Senior Lecturer",
                        "it": "docente senior"
                    },
                    "description": {
                        "fr": "Raoul Fernando est un maître de conférence",
                        "en": "Raoul Fernando is a Senior Lecturer",
                        "es": "Raoul Fernando es un conferenciante sénior",
                        "de": "Raoul Fernando ist Senior Lecturer",
                        "it": "Raoul Fernando è un docente senior"
                    },
                    "image": "/uploads/9-michel-free.jpg1528384158.jpeg"
                }'
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
            '{
                    "name": "Testo Speaker",
                    "title": {
                        "fr": "Maître de conférence",
                        "en": "Senior Lecturer",
                        "es": "Conferenciante senior",
                        "de": "Senior Lecturer",
                        "it": "docente senior"
                    },
                    "description": {
                        "fr": "Raoul Fernando est un maître de conférence",
                        "en": "Raoul Fernando is a Senior Lecturer",
                        "es": "Raoul Fernando es un conferenciante sénior",
                        "de": "Raoul Fernando ist Senior Lecturer",
                        "it": "Raoul Fernando è un docente senior"
                    },
                    "image": "/uploads/9-michel-free.jpg1528384158.jpeg"
                }'
        );
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    // test not found
    
    public function testDeleteNotFoundSpeaker()
    {
        $client = self::createClient();
        $crawler = $client->request('DELETE', '/api/speaker/5000');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
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
            '{
                    "name": "Raoul Fernando",
                    "title": {
                        "fr": "Maître de conférence",
                        "en": "Senior Lecturer",
                        "es": "Conferenciante senior",
                        "de": "Senior Lecturer",
                        "it": "docente senior"
                    },
                    "description": {
                        "fr": "Raoul Fernando est un maître de conférence",
                        "en": "Raoul Fernando is a Senior Lecturer",
                        "es": "Raoul Fernando es un conferenciante sénior",
                        "de": "Raoul Fernando ist Senior Lecturer",
                        "it": "Raoul Fernando è un docente senior"
                    },
                    "image": "/uploads/9-michel-free.jpg1528384158.jpeg"
                }'
        );
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testVersionNotFoundSpeaker()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/speaker/5000/version');
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
        $crawler = $client->request('GET', '/api/speaker/5/version');

        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"' // optional message shown on failure
        );
    }
    
    public function testReturnedReverseSpeaker()
    {
        $client = self::createClient();
        $crawler = $client->request('PUT', '/api/speaker/5/1');

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
        $response = $client->getResponse();
        $response = $response->getContent();
        $arrayResponse = json_decode($response, true);
        $speakerTest = [
            "id" => 6,
            "name" => "Raoul Fernando",
            "title" => [
                "fr" => "Maître de conférence",
                "en" => "Senior Lecturer",
                "es" => "Conferenciante senior",
                "de" => "Senior Lecturer",
                "it" => "docente senior"
            ],
            "description" => [
                "fr" => "Raoul Fernando est un maître de conférence",
                "en" => "Raoul Fernando is a Senior Lecturer",
                "es" => "Raoul Fernando es un conferenciante sénior",
                "de" => "Raoul Fernando ist Senior Lecturer",
                "it" => "Raoul Fernando è un docente senior"
            ],
            "image" => "/uploads/9-michel-free.jpg1528384158.jpeg",
            "position" => 0
        ];
        $this->assertTrue(
            $arrayResponse === $speakerTest
        );
    }

    public function testReturnedJsonListSpeaker()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/speaker');
        $response = $client->getResponse();
        $response = $response->getContent();
        $arrayResponse = json_decode($response, true);

        $speakerListTest = [
            0 =>[
                "id" => 6,
                "name" => "Raoul Fernando",
                "title" => [
                "fr" => "Maître de conférence",
                "en" => "Senior Lecturer",
                "es" => "Conferenciante senior",
                "de" => "Senior Lecturer",
                "it" => "docente senior",
                ],
                "description" => [
                "fr" => "Raoul Fernando est un maître de conférence",
                "en" => "Raoul Fernando is a Senior Lecturer",
                "es" => "Raoul Fernando es un conferenciante sénior",
                "de" => "Raoul Fernando ist Senior Lecturer",
                "it" => "Raoul Fernando è un docente senior"
                ],
                "image" => "/uploads/9-michel-free.jpg1528384158.jpeg",
                "position" => 0
                ],
            1 => [
                "id" => 5,
                "name" => "Jean Gabin",
                "title" => [
                "fr" => "Intervenant",
                "en" => "",
                "es" => "",
                "de" => "",
                "it" => ""
                ],
                "description" => [
                "fr" => "Jean Gabin est un Intervenant",
                "en" => "",
                "es" => "",
                "de" => "",
                "it" => ""
                ],
                "image" => "http://via.placeholder.com/340x200",
                "position" => 1
            ],
            2 => [
                "id" => 7,
                "name" => "Héléne Perchi",
                "title" => [
                "fr" => "Animatrice pour enfants",
                "en" => "Children's facilitator",
                "es" => "facilitadora de los niños",
                "de" => "Kindervermittlerin",
                "it" => "facilitatrice per bambini",
                ],
                "description" => [
                "fr" => "Héléne Perchi est une animatrice pour enfants",
                "en" => "Héléne Perchi is a children's facilitator",
                "es" => "Héléne Perchi es una facilitadora de los niños",
                "de" => "Héléne Perchi ist eine Kindervermittlerin",
                "it" => "Héléne Perchi è una facilitatrice per bambini"
                ],
                "image" => "/uploads/images (3)1528384143.jpeg",
                "position" => 2
            ],
            3 => [
                "id" => 8,
                "name" => "Testo Speaker",
                "title" =>[
                "fr" => "Maître de conférence",
                "en" => "Senior Lecturer",
                "es" => "Conferenciante senior",
                "de" => "Senior Lecturer",
                "it" => "docente senior"
                ],
                "description" => [
                "fr" => "Raoul Fernando est un maître de conférence",
                "en" => "Raoul Fernando is a Senior Lecturer",
                "es" => "Raoul Fernando es un conferenciante sénior",
                "de" => "Raoul Fernando ist Senior Lecturer",
                "it" => "Raoul Fernando è un docente senior",
                ],
                "image" => "/uploads/9-michel-free.jpg1528384158.jpeg",
                "position" => 3,
            ]
            ];
        $this->assertTrue(
            $arrayResponse === $speakerListTest
        );
    }

    public function testReturnedJsonVersionSpeaker()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/speaker/5/1');
        $response = $client->getResponse();
        $response = $response->getContent();
        $arrayResponse = json_decode($response, true);
        $speakerVersionTest = [
            "name" => "Jean Gabin",
            "title" =>[
                "fr" => "Intervenant",
                "en" => "",
                "es" => "",
                "de" => "",
                "it" => "",
            ],
            "description" =>[
                "fr" => "Jean Gabin est un Intervenant",
                "en" => "",
                "es" => "",
                "de" => "",
                "it" => "",
            ],
            "image" => "http://via.placeholder.com/340x200",
            "id" => "5",
            ];
        $this->assertTrue(
            $arrayResponse === $speakerVersionTest
        );
    }

    public function testReturnedJsonSetPosSpeaker()
    {
        $client = self::createClient();
        $crawler = $client->request('PUT', '/api/speaker/5/position/1');
        $response = $client->getResponse();
        $response = $response->getContent();
        $arrayResponse = json_decode($response, true);
        $speakerVersionTest = [
            0 =>[
                "id" => 6,
                "name" => "Raoul Fernando",
                "title" => [
                "fr" => "Maître de conférence",
                "en" => "Senior Lecturer",
                "es" => "Conferenciante senior",
                "de" => "Senior Lecturer",
                "it" => "docente senior",
                ],
                "description" => [
                "fr" => "Raoul Fernando est un maître de conférence",
                "en" => "Raoul Fernando is a Senior Lecturer",
                "es" => "Raoul Fernando es un conferenciante sénior",
                "de" => "Raoul Fernando ist Senior Lecturer",
                "it" => "Raoul Fernando è un docente senior"
                ],
                "image" => "/uploads/9-michel-free.jpg1528384158.jpeg",
                "position" => 0
                ],
            1 => [
                "id" => 5,
                "name" => "Jean Gabin",
                "title" => [
                "fr" => "Intervenant",
                "en" => "",
                "es" => "",
                "de" => "",
                "it" => ""
                ],
                "description" => [
                "fr" => "Jean Gabin est un Intervenant",
                "en" => "",
                "es" => "",
                "de" => "",
                "it" => ""
                ],
                "image" => "http://via.placeholder.com/340x200",
                "position" => 1
            ],
            2 => [
                "id" => 7,
                "name" => "Héléne Perchi",
                "title" => [
                "fr" => "Animatrice pour enfants",
                "en" => "Children's facilitator",
                "es" => "facilitadora de los niños",
                "de" => "Kindervermittlerin",
                "it" => "facilitatrice per bambini",
                ],
                "description" => [
                "fr" => "Héléne Perchi est une animatrice pour enfants",
                "en" => "Héléne Perchi is a children's facilitator",
                "es" => "Héléne Perchi es una facilitadora de los niños",
                "de" => "Héléne Perchi ist eine Kindervermittlerin",
                "it" => "Héléne Perchi è una facilitatrice per bambini"
                ],
                "image" => "/uploads/images (3)1528384143.jpeg",
                "position" => 2
            ],
            3 => [
                "id" => 8,
                "name" => "Testo Speaker",
                "title" =>[
                "fr" => "Maître de conférence",
                "en" => "Senior Lecturer",
                "es" => "Conferenciante senior",
                "de" => "Senior Lecturer",
                "it" => "docente senior"
                ],
                "description" => [
                "fr" => "Raoul Fernando est un maître de conférence",
                "en" => "Raoul Fernando is a Senior Lecturer",
                "es" => "Raoul Fernando es un conferenciante sénior",
                "de" => "Raoul Fernando ist Senior Lecturer",
                "it" => "Raoul Fernando è un docente senior",
                ],
                "image" => "/uploads/9-michel-free.jpg1528384158.jpeg",
                "position" => 3,
            ]
            ];
        $this->assertTrue(
            $arrayResponse === $speakerVersionTest
        );
    }

    public function testReturnedJsonVersionLogSpeaker()
    {
        $client = self::createClient();
        $crawler = $client->request('PUT', '/api/speaker/5/version');
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
