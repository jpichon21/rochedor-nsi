<?php

/*
 * (c) Logomotion <production@logomotion.fr>
 *
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Speaker;
use AppBundle\Service\PageService;

class DefaultController extends Controller
{
    /**
     * @var PageService
     */
    private $pageService;

    public function __construct(PageService $pageService)
    {
        $this->pageService = $pageService;
    }
     /**
     * @Route("/", name="home", defaults={"_locale"="fr"}, requirements={"_locale" = "%locales%"})
     * @Route("/{_locale}/", name="home_locale", requirements={"_locale" = "%locales%"})
     */
    public function indexAction(Request $request)
    {
        $locale = $this->get('translator')->getLocale();
        $contentDocument = $this->pageService->getContent($locale);
        $news = $this->getDoctrine()->getRepository('AppBundle:News')->findForHomepage($contentDocument->getLocale());

        // Masque l'animation d'intro si elle a déjà été affichée
        $showIntro = false;
        if (is_null($this->get('session')->get('showIntro'))) {
            $showIntro = true;
            $this->get('session')->set('showIntro', true);
        }

        return $this->render('default/index.html.twig', array(
            'page' => $contentDocument,
            'availableLocales' => $this->pageService->getAvailableLocales($contentDocument),
            'news' => $news,
            'showIntro' => $showIntro
        ));
    }

    /**
     * @Route("/{_locale}/intervenants", name="speakers-fr")
     * @Route("/{_locale}/speakers", name="speakers-en")
     * @Route("/{_locale}/lautsprecher", name="speakers-de")
     * @Route("/{_locale}/altoparlanti", name="speakers-it")
     * @Route("/{_locale}/altavoces", name="speakers-es")
     */
    public function showSpeakerAction(Request $request)
    {
        $path = $request->getPathInfo();
        $name = substr($path, 1);
        $page = $this->pageService->getContentFromRequest($request);
        if (!$page) {
            throw $this->createNotFoundException($this->translator->trans('global.page-not-found'));
        }
        $availableLocales = $this->pageService->getAvailableLocales($page);
        $speakers = $this->getDoctrine()->getRepository('AppBundle:Speaker')->findAllOrderByPos();
        foreach ($speakers as $speaker) {
            $localSpeaker = new Speaker;
            $localeTitle = $speaker->getTitle()[$page->getLocale()];
            $localeDesc = $speaker->getDescription()[$page->getLocale()];
            $localSpeaker->setName($speaker->getName());
            $localSpeaker->setTitle($localeTitle);
            $localSpeaker->setDescription($localeDesc);
            $localSpeaker->setImage($speaker->getImage());
            $localSpeaker->setPosition($speaker->getPosition());
            $speakers[] = $localSpeaker;
            unset($speakers[array_search($speaker, $speakers)]);
        }
        return $this->render('default/speaker.html.twig', array(
            'page' => $page,
            'availableLocales' => $availableLocales,
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
            'availableLocales' => $this->pageService->getAvailableLocales($contentDocument)
        ));
    }

    /**
     * @Route("/{locale}/getterms", name="getcgv")
     */
    public function getTermsAction()
    {
        $user = $this->getUser();
        if ($user->getProfessionnel() === true) {
            return $this->redirectToRoute('cgv-pro');
        }
        return $this->redirectToRoute('cgv');
    }

    /**
     * @Route("/maintenance", name="maintenance", defaults={"_locale"="fr"}, requirements={"_locale" = "%locales%"})
     */
    public function maintenanceAction(Request $request)
    {
        return $this->render('default/maintenance.html.twig');
    }
}
