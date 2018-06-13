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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Contact;
use AppBundle\Entity\CalL;

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
        $typeRetraite =$calendarRepo->findTypesRetraites(19);
        $calendrier =$calendarRepo->findCalendrier('2018-06-09', 'RET', 'Roch', 'coAct');
        dump($calendrier);
        exit;
        dump($sites);
        exit;
        return new Response('test');
    }

    // $contact = new Contact;
    // $civil = $request->get('civil');
    // $civil2 = $request->get('civil2');
    // $nom = $request->get('nom');
    // $prenom = $request->get('prenom');
    // $adresse = $request->get('adresse');
    // $cp = $request->get('cp');

    public function individualInscription(Request $request, Contact $contact, $Jsco, $CodCal)
    {
        $contact->setJsco($Jsco);
        $this->incrementInscr($request->get('lieu'));
        $calL= new CalL;
        $calL->setCodcal($CodCal);
        $calL->getLcal($request->get('lcal'));
        $calL->getTyplcal($request->get('typlcal'));
        $calL->getRefLcal($request->get('reflcal'));
        $calL->getJslcal($request->get('jslcal'));
        $em = $this->getDoctrine()->getManager();
        $em->persist($calL);
        $em->flush();
    }
    
    public function incrementInscr($lieu)
    {
        $year = date('Y');
        $nom = "ins".$lieu.substr($year, -2);
        $variable = $this->getDoctrine()->getRepository('AppBundle:Variable')->findByNom($nom);
        $variable->setValeurn($variable->getValeurn()+1);
        $em->persist($variable);
        $em->flush();
    }
}
