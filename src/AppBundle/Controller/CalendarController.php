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
        $eventTypes =$calendarRepo->findEventTypes();
        $events =$calendarRepo->findEvents();
        return new Response('test');
    }

    public function updateContact(Request $request)
    {
        
        $em = $this->getDoctrine()->getManager();
        $codco = $request->get('codco');
        $contact = $em->getRepository('AppBundle:Contact')->findOneById($codco);
        $civil = $request->get('civil');
        $civil2 = $request->get('civil2');
        $nom = $request->get('nom');
        $prenom = $request->get('prenom');
        $adresse = $request->get('adresse');
        $cp = $request->get('cp');
        $ville = $request->get('ville');
        $tel = $request->get('tel');
        $mobil = $request->get('mobil');
        $email = $request->get('email');
        $profession = $request->get('profession');
        $mpco = $request->get('mpco');
        $datenaiss = $request->get('datnaiss');

        $contact->setCivil($civil);
        $contact->setCivil2($civil2);
        $contact->setNom($nom);
        $contact->setPrenom($prenom);
        $contact->setAdresse($adresse);
        $contact->setCp($cp);
        $contact->setVille($ville);
        $contact->setTel($tel);
        $contact->setMobil($mobil);
        $contact->setEmail($email);
        $contact->setProfession($profession);
        $contact->setMpco($mpco);
        $contact->setDatenaiss($datenaiss);
        $em->persist($contact);
        $em->flush();
    }

    public function persistContact(Request $request)
    {
        
        $contact = new Contact;
        $em = $this->getDoctrine()->getManager();
        $civil = $request->get('civil');
        $civil2 = $request->get('civil2');
        $nom = $request->get('nom');
        $prenom = $request->get('prenom');
        $adresse = $request->get('adresse');
        $cp = $request->get('cp');
        $ville = $request->get('ville');
        $tel = $request->get('tel');
        $mobil = $request->get('mobil');
        $email = $request->get('email');
        $profession = $request->get('profession');
        $mpco = $request->get('mpco');
        $datenaiss = $request->get('datnaiss');

        $contact->setCivil($civil);
        $contact->setCivil2($civil2);
        $contact->setNom($nom);
        $contact->setPrenom($prenom);
        $contact->setAdresse($adresse);
        $contact->setCp($cp);
        $contact->setVille($ville);
        $contact->setTel($tel);
        $contact->setMobil($mobil);
        $contact->setEmail($email);
        $contact->setProfession($profession);
        $contact->setMpco($mpco);
        $contact->setDatenaiss($datenaiss);
        $em->persist($contact);
        $em->flush();
    }

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
