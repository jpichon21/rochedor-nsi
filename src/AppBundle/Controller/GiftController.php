<?php

/*
 * (c) Logomotion <production@logomotion.fr>
 *
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Repository\CalendarRepository;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Repository\TpaysRepository;
use AppBundle\Service\PageService;

/**
 * @Route("/{_locale}")
 */
class GiftController extends Controller
{
    /**
     * @var PageService
     */
    private $pageService;

    public function __construct(
        TpaysRepository $tpaysRepository,
        PageService $pageService
    ) {
        $this->tpaysRepository = $tpaysRepository;
        $this->pageService = $pageService;
    }

    /**
     * @Route("/dons", name="gift-fr")
     * @Route("/donations", name="gift-en")
     * @Route("/spenden", name="gift-de")
     * @Route("/donazioni", name="gift-it")
     * @Route("/donaciones", name="gift-es")
     */
    public function calendarAction(Request $request)
    {
        $countriesJSON = array();
        $countries = $this->tpaysRepository->findAllCountry();
        foreach ($countries as $country) {
            $countriesJSON[] = array(
                'codpays' => $country->getCodpays(),
                'nompays' => $country->getNompays()
            );
        }

        $page = $this->pageService->getContentFromRequest($request);
        $availableLocales = $this->pageService->getAvailableLocales($page);
        return $this->render('default/gift.html.twig', [
            'page' => $page,
            'availableLocales' => array(),
            'countries' => $countriesJSON
        ]);
    }
}
