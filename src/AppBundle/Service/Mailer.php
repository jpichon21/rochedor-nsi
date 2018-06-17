<?php
namespace AppBundle\Service;

use \Swift_Mailer;
use \Swift_Message;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Mailer
{
    private $mailer;
    private $container;
    private $from;
    private $name;

    public function __construct(Swift_Mailer $mailer, ContainerInterface $container)
    {
        $this->mailer = $mailer;
        $this->container = $container;
        $this->from = $container->getParameter('email_from_address');
        $this->name = $container->getParameter('email_from_name');
    }
    /**
     * Send an email
     * @param string|array $to The recipient address, should be a string or an array
     * @param string $subject The email subject
     * @param string $body The email body, should contain html, will be placed in the body part of the template
     * @param array $link Optionnal link to display at the end of the email.
     * @param string $template The email template to use
     */
    public function send($to, $subject, $body, $link = null, $template = null)
    {
        $template = ($template) ? $template : 'default';
        $message = (new Swift_Message($subject))
        ->setFrom($this->from, $this->name)
        ->setTo($to)
        ->setBody(
            $this->container->get('templating')->render(
                'emails/' . $template . '.html.twig',
                [
                    'body' => $body,
                    'link' => $link
                ]
            ),
            'text/html'
        )
        ->addPart(
            $this->container->get('templating')->render(
                'emails/' . $template . '.txt.twig',
                [
                    'body' => $body,
                    'link' => $link
                ]
            ),
            'text/plain'
        );
        try {
            return $this->mailer->send($message);
        } catch (\Swift_SwiftException $e) {
            echo $e->getMessage();
        }
    }
}
