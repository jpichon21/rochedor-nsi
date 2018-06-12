<?php

/*
 * (c) Logomotion <production@logomotion.fr>
 *
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Repository\CalendarRepository;

class CalendarController extends Controller
{

    /**
     * @Route("/calendar", name="calendar")
     *
     * @return void
     */
    public function showCalendarAction(CalendarRepository $calendarRepo)
    {
        $sites = $calendarRepo->findSites();
        dump($sites);
        return new Response('test');
    }
}
