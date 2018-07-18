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

class ContactController extends Controller
{
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
    * @Route("/contact-ro", name="contact-ro")
    * @Route("/kontakt-ro", name="kontakt-ro")
    * @Route("/contactar-ro", name="contactar-ro")
    * @Route("/contact-us-ro", name="contact-us-ro")
    * @Route("/contáctenos-ro", name="contáctenos-ro")
    */
    public function showContactRo(Request $request)
    {
        $data = [];
        $form = $this->createForm(ContactType::class, $data);

        $form->handleRequest($request);
        $path = $request->getPathInfo();
        $name = substr($path, 1);
        $contentDocument = $this->pageService->getContent($name);

        if ($form->isSubmitted() && $form->isValid()) {
            $mail = $form->getData();
            $mail['site'] = "Roche d'Or";

            $this->mailer->send(
                'secretariat@rochedor.fr',
                $this->translator->trans('contact.ro.foradmin.subject').' '.$mail['name'].' '.$mail['surname'],
                $this->renderView('emails/contact/contact-admin.html.twig', [
                    'mail' => $mail
                    ])
            );
                
            $this->mailer->send(
                $mail['email'],
                $this->translator->trans('contact.ro.forclient.subject'),
                $this->renderView('emails/contact/locale/contact-client-'.$request->getLocale().'.html.twig', [
                    'mail' => $mail
                    ])
            );

            return $this->render('default/contact-success.html.twig', array(
                'page' => $contentDocument,
                'availableLocales' => $this->pageService->getAvailableLocales($contentDocument)
            ));
        }

        return $this->render('default/contact.html.twig', array(
            'form' => $form->createView(),
            'page' => $contentDocument,
            'availableLocales' => $this->pageService->getAvailableLocales($contentDocument)
        ));
    }

    /**
    * @Route("/contact-ft", name="contact-ft")
    * @Route("/kontakt-ft", name="kontakt-ft")
    * @Route("/contactar-ft", name="contactar-ft")
    * @Route("/contact-us-ft", name="contact-us-ft")
    * @Route("/contáctenos-ft", name="contáctenos-ft")
    */
    public function showContactFont(Request $request)
    {
        
        $data = [];
        $form = $this->createForm(ContactType::class, $data);

        $form->handleRequest($request);
        $path = $request->getPathInfo();
        $name = substr($path, 1);
        $contentDocument = $this->pageService->getContent($name);
        if ($form->isSubmitted() && $form->isValid() && $this->captchaverify($request->get('g-recaptcha-response'))) {
            $mail = $form->getData();
            $mail['site'] = "Fontanilles";
            
            $this->mailer->send(
                'secretariat@rochedor.fr',
                $this->translator->trans('contact.font.foradmin.subject').' '.$mail['name'].' '.$mail['surname'],
                $this->renderView('emails/contact/contact-admin.html.twig', [
                    'mail' => $mail
                    ])
            );
                
            $this->mailer->send(
                $mail['email'],
                $this->translator->trans('contact.font.forclient.subject'),
                $this->renderView('emails/contact/locale/contact-client-'.$request->getLocale().'.html.twig', [
                    'mail' => $mail
                    ])
            );

            return $this->render('default/contact-success.html.twig', array(
                'page' => $contentDocument,
                'availableLocales' => $this->pageService->getAvailableLocales($contentDocument)
            ));
        }

        return $this->render('default/contact.html.twig', array(
            'form' => $form->createView(),
            'page' => $contentDocument,
            'availableLocales' => $this->pageService->getAvailableLocales($contentDocument)
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
            'secret' => '6LdgnGQUAAAAAL-S38oSIPzMm85iWCG1vIoZX-mL',
            'response' => $recaptcha
        ));
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response);

        return $data->success;
    }
}
