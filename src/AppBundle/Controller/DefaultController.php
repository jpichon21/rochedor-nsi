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
use AppBundle\Entity\Page;
use AppBundle\Entity\News;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;

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
        $availableLocales = array();
        if ($locale === "fr") {
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

        $news = $this->getDoctrine()->getRepository('AppBundle:News')->findCurrent($contentDocument->getLocale());
        $lastNews = array_shift($news);
        return $this->render('default/index.html.twig', array(
            'page' => $contentDocument,
            'availableLocales' => $availableLocales,
            'lastNews' => $lastNews
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
        $availableLocales = array();
        if ($contentDocument->getLocale() === "fr") {
            $cm = $contentDocument->getChildren();
            $myChild = $cm->getValues();
        } else {
            $cm = $contentDocument->getParent();
            $mc = $cm->getChildren();
            $myChild = $mc->getValues();
            $tmpRoute = $cm->getRoutes()->getValues();
            $availableLocales['fr'] = $tmpRoute[0]->getStaticPrefix();
        }
        foreach ($myChild as $childPage) {
            if ($childPage->getLocale() != $contentDocument->getLocale()) {
                $key = $childPage->getLocale();
                $tmp = $childPage->getRoutes()->getValues();
                $availableLocales[$key] = $tmp[0]->getStaticPrefix();
            }
        }

        return $this->render('default/page.html.twig', array(
            'page' => $contentDocument,
            'availableLocales' => $availableLocales
        ));
    }
}
