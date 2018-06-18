<?php

/*
 * (c) Logomotion <production@logomotion.fr>
 *
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\ServiceShowPage;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\PageController;
use AppBundle\Service\Mailer;
use Symfony\Component\Translation\TranslatorInterface as Translator;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
        Translator $translator
    ) {
        $this->mailer = $mailer;
        $this->translator = $translator;
    }

    /**
    * @Route("/contact-ro", name="contact-ro")
    * @Route("/kontakt-ro", name="kontakt-ro")
    * @Route("/contactar-ro", name="contactar-ro")
    * @Route("/contact-us-ro", name="contact-us-ro")
    * @Route("/contáctenos-ro", name="contáctenos-ro")
    */
    public function showContactRo(Request $request, ServiceShowPage $showPage)
    {
        $data = [];
        $form = $this->createForm(ContactType::class, $data);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $mail = $form->getData();
            $mail['site'] = "la Roche d'Or";

            $this->mailer->send(
                $mail['mail'],
                $this->translator->trans('contact.ro.foradmin.subject').$mail['name'].' '.$mail['surname'],
                $this->renderView('emails/contact/contact-admin.html.twig', [
                    'mail' => $mail
                    ])
            );

            $this->mailer->send(
                'secretariat@rochedor.fr',
                $this->translator->trans('contact.ro.foradmin.subject'),
                $this->renderView('emails/contact/locale/contact-client-'.$request->getLocale().'.html.twig', [
                    'mail' => $mail
                    ])
            );
        }

        $path = $request->getPathInfo();
        $name = substr($path, 1);
        $contentDocument = $showPage->getMyContent($name);
        return $this->render('default/contact-ro.html.twig', array(
            'form' => $form->createView(),
            'page' => $contentDocument,
            'availableLocales' => $this->getAvailableLocales($contentDocument)
        ));
    }

    /**
    * @Route("/contact-font", name="contact-font")
    * @Route("/kontakt-font", name="kontakt-font")
    * @Route("/contactar-font", name="contactar-font")
    * @Route("/contact-us-font", name="contact-us-font")
    * @Route("/contáctenos-font", name="contáctenos-font")
    */
    public function showContactFont(Request $request, ServiceShowPage $showPage)
    {
        
        $data = [];
        $form = $this->createForm(ContactType::class, $data);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $mail = $form->getData();
            $mail['site'] = "les Fontanilles";
            
            $this->mailer->send(
                $mail['mail'],
                $this->translator->trans('contact.foradmin.subject').$mail['name'].' '.$mail['surname'],
                $this->renderView('emails/contact/contact-admin.html.twig', [
                    'mail' => $mail
                    ])
            );

            $this->mailer->send(
                'secretariat@rochedor.fr',
                $this->translator->trans('contact.font.client.subject'),
                $this->renderView('emails/contact/locale/contact-client-'.$request->getLocale().'.html.twig', [
                    'mail' => $mail
                    ])
            );
        }

        $path = $request->getPathInfo();
        $name = substr($path, 1);
        $contentDocument = $showPage->getMyContent($name);
        return $this->render('default/contact-font.html.twig', array(
            'form' => $form->createView(),
            'page' => $contentDocument,
            'availableLocales' => $this->getAvailableLocales($contentDocument)
        ));
    }

    public function getAvailableLocales($contentDocument)
    {
        $availableLocales = array();
        if ($contentDocument->getLocale() === "fr") {
            $cm = $contentDocument->getChildren();
            $myChild = $cm->getValues();
        } else {
            $cm = $contentDocument->getParent();
            $mc = $cm->getChildren();
            $myChild = $mc->getValues();
            $tmpP = $cm->getRoutes()->getValues();
            $availableLocales['fr'] = $tmpP[0]->getStaticPrefix();
        }
        foreach ($myChild as $childPage) {
            if ($childPage->getLocale() != $contentDocument->getLocale()) {
                $key = $childPage->getLocale();
                $tmp = $childPage->getRoutes()->getValues();
                $availableLocales[$key] = $tmp[0]->getStaticPrefix();
            }
        }
        return $availableLocales;
    }
}
