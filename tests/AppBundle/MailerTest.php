<?php
namespace Tests\AppBundle;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Service\Mailer;

class MailerTest extends WebTestCase
{

    const SUBJECT = 'Email subject';
    const BODY = 'Email body';
    const TO = 'production@logomotion.fr';

    /**
     * @var Mailer
     */
    private $mailer;

    public function testEmailDefault()
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();
        $client = static::createClient();
        $container = $client->getContainer();
        $mailer = $container->get('app.mailer');
        $this->assertTrue($mailer->send($this::TO, $this::SUBJECT, $this::BODY) === 1);
    }
}
