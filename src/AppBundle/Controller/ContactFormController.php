<?php

/*
 * (c) Logomotion <production@logomotion.fr>
 *
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\PageController;
use AppBundle\Service\Mailer;
use Symfony\Component\Translation\TranslatorInterface as Translator;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Service\PageService;
use AppBundle\Form\ContactType;

class ContactFormController extends Controller
{
    const SITES = [
        "ro" => "Roche d'Or",
        "ft" => "Fontanilles"
    ];

    /**
    * @var Mailer
    */
    private $mailer;

    /**
     * @var Translator
     */
    private $translator;

    public function __construct(
        Mailer $mailer,
        Translator $translator,
        PageService $pageService
    ) {
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->pageService = $pageService;
    }

    /**
    * @Route("{_locale}/contact-{site}", name="contact-fr")
    * @Route("{_locale}/kontakt-{site}", name="contact-de")
    * @Route("{_locale}/contactar-{site}", name="contact-es")
    * @Route("{_locale}/contact-us-{site}", name="contact-en")
    * @Route("{_locale}/contactenos-{site}", name="contact-it")
    */
    public function showContactRo(Request $request, $site)
    {
        $data = [];
        $form = $this->createForm(ContactType::class, $data);

        $form->handleRequest($request);
        $path = $request->getPathInfo();
        $name = substr($path, 1);
        $page = $this->pageService->getContentFromRequest($request);
        if (!$page) {
            throw $this->createNotFoundException($this->translator->trans('global.page-not-found'));
        }
        $availableLocales = $this->pageService->getAvailableLocales($page);
        

        if ($form->isSubmitted() && $form->isValid()) {
            $mail = $form->getData();
            $mail['site'] = $this::SITES[$site];

            $this->mailer->send(
                $this->getParameter('email_contact_address'),
                $this->translator->trans('contact.'.$site.'.foradmin.subject').' '.$mail['name'].' '.$mail['surname'],
                $this->renderView('emails/contact/contact-admin.html.twig', [
                    'mail' => $mail
                    ])
            );
                
            $this->mailer->send(
                $mail['email'],
                $this->translator->trans('contact.'.$site.'.forclient.subject'),
                $this->renderView('emails/contact/locale/contact-client-'.$request->getLocale().'.html.twig', [
                    'mail' => $mail
                    ])
            );

            return $this->render('default/contact-success.html.twig', array(
                'page' => $page,
                'availableLocales' => $availableLocales
            ));
        }

        return $this->render('default/contact.html.twig', array(
            'form' => $form->createView(),
            'page' => $page,
            'recaptcha_key' => $this->getParameter('recaptcha_key'),
            'availableLocales' => $availableLocales
        ));
    }

    public function captchaverify($recaptcha)
    {
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            'secret' => $this->getParameter('recaptcha_secret'),
            'response' => $recaptcha
        ));
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response);

        return $data->success;
    }
}
