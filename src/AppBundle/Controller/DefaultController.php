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
use AppBundle\Controller\CalendarController;
use AppBundle\Entity\Page;
use AppBundle\Entity\News;
use AppBundle\Entity\Speaker;

class DefaultController extends Controller
{
     /**
     * @Route("/", name="home", defaults={"_locale"="fr"}, requirements={"_locale" = "%locales%"})
     * @Route("/{_locale}/", name="home_locale", requirements={"_locale" = "%locales%"})
     */
    public function indexAction(Request $request, ServiceShowPage $showPage)
    {
        $locale = $this->get('translator')->getLocale();
        $contentDocument = $showPage->getMyContent($locale);
        $news = $this->getDoctrine()->getRepository('AppBundle:News')->findCurrent($contentDocument->getLocale());
        $lastNews = array_shift($news);
        return $this->render('default/index.html.twig', array(
            'page' => $contentDocument,
            'availableLocales' => $this->getAvailableLocales($contentDocument),
            'lastNews' => $lastNews
        ));
    }

    /**
     * @Route("/intervenants", name="intervenants")
     * @Route("/speakers", name="speakers")
     * @Route("/lautsprecher", name="lautsprecher")
     * @Route("/altoparlanti", name="altoparlanti")
     * @Route("/altavoces", name="altavoces")
     */
    public function showSpeakerAction(Request $request, ServiceShowPage $showPage)
    {
        $path = $request->getPathInfo();
        $name = substr($path, 1);
        $contentDocument = $showPage->getMyContent($name);
        $speakers = $this->getDoctrine()->getRepository('AppBundle:Speaker')->findAll();
        foreach ($speakers as $speaker) {
            $localSpeaker = new Speaker;
            $localeTitle = $speaker->getTitle()[$contentDocument->getLocale()];
            $localeDesc = $speaker->getDescription()[$contentDocument->getLocale()];
            $localSpeaker->setName($speaker->getName());
            $localSpeaker->setTitle($localeTitle);
            $localSpeaker->setDescription($localeDesc);
            $localSpeaker->setImage($speaker->getImage());
            $localSpeaker->setPosition($speaker->getPosition());
            $speakers[] = $localSpeaker;
            unset($speakers[array_search($speaker, $speakers)]);
        }
        return $this->render('default/speaker.html.twig', array(
            'page' => $contentDocument,
            'availableLocales' => $this->getAvailableLocales($contentDocument),
            'speakers'=> $speakers
        ));
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function adminAction()
    {
        return $this->render('admin/index.html.twig');
    }

    public function showPageAction($contentDocument)
    {
        return $this->render('default/page.html.twig', array(
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
