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
use AppBundle\Entity\ContactL;
use AppBundle\Entity\CalL;
use AppBundle\ServiceShowPage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Page;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations as Rest;

class CalendarController extends Controller
{
    const YEARS_ADULT = 16;
    const RELATIONS = ['child' => 'enfan', 'spouse' => 'conjo'];
    const SITES = [
        [
            "value" => "lrdo",
            "name" => "La Roche d'Or",
            "abbr" => "RO",
            "color" => "#ff00ff"
        ],
        [
            "value" => "font",
            "name" => "Les Fontanilles",
            "abbr" => "FT",
            "color" => "#0000ff"
        ]
    ];

    const COLORS = [
        "F" => "#E10076",
        "A" => "#55C055",
        "W" => "#00B6E8",
        "TP" => "#FFA500",
        "B" => "#0D0479",
        "RI" => "#008000",
        "FA" => "#8833CC",
        "P" => "#671C43",
        "R" => "#31BF31",
        "J" => "#F7F752",
        "L" => "#AD58F2",
        "RC" => "#AD58F2",
        "Autre" => "#E10076"
    ];

    /**
     * @var CalendarRepository
     */
    private $repository;

    public function __construct(CalendarRepository $repository)
    {
        $this->repository = $repository;
    }

     /**
     * @Route("/inscription-retraite", name="inscription_retraite")
     * @Route("/registration-retreat", name="registration_retreat")
     * @Route("/anmeldung-ruhestand", name="anmeldung_ruhestand")
     * @Route("/iscrizione-ritiro", name="iscrizione_ritiro")
     * @Route("/registro-jubilado", name="registro_jubilado")
     */
    public function calendarRegistrationAction(Request $request, ServiceShowPage $showPage)
    {
        $path = $request->getPathInfo();
        $name = substr($path, 1);
        $contentDocument = $showPage->getMyContent($name);
        return $this->render('default/calendar-registration.html.twig', array(
            'page' => array(
                'locale' => 'fr',
                'background' => 'http://localhost:8000/assets/images/background-flotype.cd2c708b.png',
                'title' => 'Demande',
                'subTitle' => 'd\'inscription',
                'content' => array(
                    'intro' => 'Introduction lorem ipsum'
                ),
                'routes' => array(
                    'getValues' => array(
                        array(
                            'staticPrefix' => 'en'
                        )
                    )
                )
            ),
            'availableLocales' => array()
        ));
    }

    /**
     * @Route("/xhr/calendar/attendees", name="xhr_calendar_post_attendees", methods="POST")
     * @Security("has_role('ROLE_USER')")
     */
    public function xhrPostAttendeesAction(Request $request)
    {
        $attendees = $request->get('attendees');
        $retirementId = $request->get('retirementId');
        if (!$attendees || !$retirementId) {
            return new JsonResponse(
                ['status' => 'ko', 'message' => 'You must provide attendees object and retirementId']
            );
        }
        if (!$this->validAttendees($attendees)) {
            return new JsonResponse(
                ['status' => 'ko', 'message' => 'The relations between attendees is not auhtorized']
            );
        }
        if (!$this->registerAttendees($attendees, $retirementId)) {
            return new JsonResponse(['status' => 'ko', 'message' => 'The registration has failed']);
        }
        return new JsonResponse(['status' => 'ok', 'message' => 'Registration successful']);
    }

    /**
     * @Route("/xhr/calendar/attendee", name="xhr_calendar_post_attendee", methods="POST")
     * @Security("has_role('ROLE_USER')")
     */
    public function xhrPostAttendeeAction(Request $request)
    {
        $attendee = $request->get('attendee');
        if (!$attendee) {
            return new JsonResponse(['status' => 'ko', 'message' => 'You must provide attendee object']);
        }
        if (isset($attendee['id'])) {
            if (!$contact = $this->repository->findContact($attendee['id'])) {
                $contact = new Contact();
            }
        } else {
            $contact = new Contact();
        }

        $em = $this->getDoctrine()->getManager();
        $this->setContact($contact, $attendee);
        $em->persist($contact);
        $em->flush();
        print_r(json_encode($contact));
        // return new JsonResponse($contact);
        // return new JsonResponse(['status' => 'ok', 'data' => $contact]);
    }

    private function registerAttendees($attendees, $retirementId)
    {
        $em = $this->getDoctrine()->getManager();
        foreach ($attendees as $a) {
            if ($contact = $this->repository->findContact($attendee['codco'])) {
                $contact = $this->setContact($contact, $attendee);
                $em->persist($contact);

                if ($a['relation'] === 'child' || $a['relation'] === 'spouse') {
                    if (!$contactl = $this->repository->findContactL($contact->getCodco(), $a['colp'])) {
                        $contactl = new ContactL();
                        $contactl->setCol($a['codco']);
                    }
                    $contactl->setColp($a['colp'])
                    ->setColt('famil')
                    ->setColtyp($this::RELATIONS[$a['relation']]);
                    $em->persist($contactl);
                }

                $registrationCount = (int) $this->repository->findRegistrationCount()['valeurn'] + 1;
                $calL = new CalL();
                $calL->setCodcal($retirementId)
                ->setLcal($contact->getCodco())
                ->setTyplcal('coIns')
                ->setReflcal($this->refCal($registrationCount));
                $em->persist($calL);
            }
        }
        $em->flush();
    }

    private function setContact(Contact $contact, $attendee)
    {
        $contact->setNom($attendee['name'])
        ->setPrenom($attendee['firstname'])
        ->setCivil($attendee['gender'])
        ->setAdresse($attendee['address'])
        ->setCp($attendee['zipcode'])
        ->setVille($attendee['city'])
        ->setPays($attendee['country'])
        ->setTel($attendee['tel'])
        ->setMobil($attendee['mobile'])
        ->setEmail($attendee['email'])
        ->setUsername($attendee['email'])
        ->setDatnaiss(new \DateTime($attendee['birthdate']))
        ->setProfession($attendee['job']);
        // print_r($contact);
        // return $contact;
    }

    private function refCal($count, $site)
    {
        $now = new \DateTime();
        $ref = strtoupper(substr($site, 0, 1));
        $ref .= $now->format('y');
        $ref .= '-'.str_pad($count, 5, '0', STR_PAD_LEFT);
        return $ref;
    }

    private function validAttendees($attendees)
    {
        foreach ($attendees as $attendee) {
            if (!$this->isAdult($attendee['birthdate'])) {
                return false;
            }
            if (!$this->hasParent($attendee, $attendees)) {
                return false;
            }
        }
        return true;
    }

    private function hasParent($child, $attendees)
    {
        if ($child['relation'] !== 'child') {
            return false;
        }
        foreach ($attendees as $attendee) {
            if ($attendee['codco'] === $attendee['colp'] && $this->isAdult($attendee['birthdate'])) {
                return true;
            }
        }
        return false;
    }

    private function isAdult(string $birthdate)
    {
        $birthdate = new \DateTime($birthdate);
        $now = new \DateTime();
        $diff = $birthdate->diff($now);
        return ($diff->y >= $this::YEARS_ADULT);
    }

    /**
     * @Route("/xhr/calendar/attendees", name="xhr_calendar_get_attendees", methods="GET")
     * @Security("has_role('ROLE_USER')")
     */
    public function xhrGetLastAttendeesAction()
    {
        $attendees = $this->getAttendees($this->getUser());
        return new JsonResponse(['status' => 'ok', 'data' => $attendees]);
    }

    private function getParents(Contact $contact)
    {
        return $this->repository->findParents($contact->getCodco());
    }

    private function getAttendees(Contact $contact)
    {
        $refs = $this->repository->findRegisteredRefs($contact->getCodco());
        if ($refs === null) {
            return null;
        }
        return $this->repository->findAttendees(array_column($refs, 'reflcal'));
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

    public function getDataCalendarAction(CalendarRepository $calendarRepo)
    {
        $data = array();
        
        $eventTypes = $calendarRepo->findEventTypes();
        $speakers = $calendarRepo->findSpeakers();
        $translations = $calendarRepo->findTranslations();
        
        foreach ($eventTypes as $eventType) {
            if ($eventType['color'] === "") {
                $key = $eventType['abbr'];
                if (isset($this::COLORS[$key])) {
                    $eventType['color'] = $this::COLORS[$key];
                } else {
                    $eventType['color'] = $this::COLORS['Autre'];
                }
            }
            $eventType['color'] = str_replace("Fond=", "", $eventType['color']);
            $eventTypes[] = $eventType;
        }

        $eventTypes = array_filter($eventTypes, function ($k) {
            return strlen($k['color']) == 7 ;
        }, ARRAY_FILTER_USE_BOTH);

        $data['sites'] = $this::SITES;
        $data['types'] = $eventTypes;
        $data['speakers']= $speakers;
        $data['translations'] = $translations;

        return $data;
    }

    public function getRetreatsData(CalendarRepository $calendarRepo)
    {
        $retreatData = array();
        $events = $calendarRepo->findEvents();
        
        foreach ($events as $event) {
            $dateIn = $event['dateIn'];
            $dateInParse = $event['dateIn']->format('Ymd');
            
            $dateOut = $event['dateOut'];
            $dateOutParse = $event['dateOut']->format('Ymd');
            
            $duration = date_diff($dateIn, $dateOut);
            $duration = substr($duration->format('%R%d'), 1);
            
            $nameEvent = $event['event'];
            
            $speakers = array();
            foreach (explode("|", $event['speakers']) as $speaker) {
                $speakerParse = array();
                $speaker = explode(" , ", $speaker);
                $speakerParse['name'] = $speaker[0] ;
                $speakerParse['value'] = $speaker[1]  ;
                $speakers[] = $speakerParse;
            }


            $type = array();
            $type['name'] = $event['typeName'];
            $type['abbr'] = $event['typeAbbr'];
            if ($event['typeColor'] === "") {
                $key = $event['typeAbbr'];
                if (isset($this::COLORS[$key])) {
                    $type['color'] = $this::COLORS[$key];
                } else {
                    $type['color'] = $this::COLORS['Autre'];
                }
                $type['color'] = str_replace("Fond=", "", $type['color']);
            }
            $type['value'] = $event['typeValue'];
            
            
            $site = array();
            if ($event['site'] === "Roch") {
                $site['abbr'] = $this::SITES[0]['abbr'];
                $site['color'] = $this::SITES[0]['color'];
            } else {
                $site['abbr'] = $this::SITES[1]['abbr'];
                $site['color'] = $this::SITES[1]['color'];
            }
            $translation = $event['translation'];
            

            $retreat['dateIn'] = $dateInParse;
            $retreat['dateOut'] = $dateOutParse;
            $retreat['site'] = $site;
            $retreat['event'] = $nameEvent;
            $retreat['type'] = $type;
            $retreat['speaker'] = $speakers;
            $retreat['translation'] = $translation;
            $retreat['duration'] = $duration;
            $retreatData[] = $retreat;
        }
        return $retreatData;
    }

    /**
     * @Route("/liste-retraites", name="liste_retraites")
     * @Route("/list-retreats", name="list_retreats")
     * @Route("/liste-ruckzuge", name="liste_ruckzuge")
     * @Route("/lista-ritiri", name="lista_ritiri")
     * @Route("/lista-retiros", name="lista_retiros")
     */
    public function calendarAction(CalendarRepository $calendarRepo)
    {
        $filters = $this->getDataCalendarAction($calendarRepo);
        $retreatsData = $this->getRetreatsData($calendarRepo);
        return $this->render('default/calendar.html.twig', array(
            'page' => array(
                'locale' => 'fr',
                'background' => 'http://localhost:8000/assets/images/background-flotype.cd2c708b.png',
                'title' => 'Demande',
                'subTitle' => 'd\'inscription',
                'content' => array(
                    'intro' => 'Introduction lorem ipsum'
                ),
                'routes' => array(
                    'getValues' => array(
                        array(
                            'staticPrefix' => 'en'
                        )
                    )
                )
            ),
            'sites' => $filters['sites'],
            'types' => $filters['types'],
            'speakers' => $filters['speakers'],
            'translations' => $filters['translations'],
            'retreatsData' => json_encode($retreatsData),
            'availableLocales' => array()
        ));
    }
}
