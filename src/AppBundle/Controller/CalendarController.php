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
use Symfony\Component\Translation\TranslatorInterface as Translator;
use AppBundle\Entity\Contact;
use AppBundle\Entity\ContactL;
use AppBundle\Entity\CalL;
use AppBundle\ServiceShowPage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Page;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Repository\ContactRepository;
use AppBundle\Service\Mailer;

class CalendarController extends Controller
{
    const YEARS_ADULT = 18;
    const YEARS_CHILD = 16;
    const SITES = [
        [
            "sitac" => "Roch",
            "value" => "lrdo",
            "name" => "La Roche d'Or",
            "abbr" => "RO",
            "color" => "#ff00ff"
        ],
        [
            "sitac" => "Font",
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
    private $calendarRepository;

    /**
     * @var ContactRepository
     */
    private $contactRepository;

    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @var Translator
     */
    private $translator;

    public function __construct(
        CalendarRepository $calendarRepository,
        ContactRepository $contactRepository,
        Mailer $mailer,
        Translator $translator
    ) {
        $this->calendarRepository = $calendarRepository;
        $this->contactRepository = $contactRepository;
        $this->mailer = $mailer;
        $this->translator = $translator;
    }

     /**
     * @Route("/inscription-retraite", name="register_calendar-fr")
     * @Route("/registration-retreat", name="register_calendar-en")
     * @Route("/anmeldung-ruhestand", name="register_calendar-de")
     * @Route("/iscrizione-ritiro", name="register_calendar-it")
     * @Route("/registro-jubilado", name="register_calendar-es")
     */
    public function calendarRegistrationAction(Request $request, ServiceShowPage $showPage)
    {
        $id = $request->query->get('id');

        if ($id) {
            $activity = $this->getCalendarAction($id);
            if ($activity) {
                $datenow = new \DateTime();
                $datefin = $activity['datfin'];
                if ($datenow < $datefin) {
                    $activity['idact'] = intval($id);
                    return $this->render('default/calendar-registration.html.twig', array(
                        'page' => array(
                            'locale' => 'fr',
                            'background' => 'http://localhost:8000/assets/images/background-flotype.cd2c708b.png',
                            'title' => 'Demande',
                            'subTitle' => 'd\'inscription',
                            'content' => array('intro' => 'Introduction lorem ipsum'),
                            'routes' => array('getValues' => array(array('staticPrefix' => 'en')))
                        ),
                        'activity' => $activity,
                        'availableLocales' => array()
                    ));
                }
                return $this->render('default/calendar-registration-error.html.twig', array(
                    'page' => array(
                        'locale' => 'fr',
                        'background' => 'http://localhost:8000/assets/images/background-flotype.cd2c708b.png',
                        'title' => 'Activité',
                        'subTitle' => 'terminée',
                        'content' => array(
                            'intro' => 'Il semblerait que cette activité ne soit plus disponible. 
                            Veuillez sélectionner une autre activité depuis le Calendrier.'),
                        'routes' => array('getValues' => array(array('staticPrefix' => 'en')))
                    ),
                    'availableLocales' => array()
                ));
            }
        }
        return $this->render('default/calendar-registration-error.html.twig', array(
            'page' => array(
                'locale' => 'fr',
                'background' => 'http://localhost:8000/assets/images/background-flotype.cd2c708b.png',
                'title' => 'Activité',
                'subTitle' => 'non trouvée',
                'content' => array(
                    'intro' => 'Il semblerait que cette activité n\'existe plus ou n\'a jamais existée. 
                    Veuillez sélectionner une activité depuis le Calendrier.'),
                'routes' => array('getValues' => array(array('staticPrefix' => 'en')))
            ),
            'availableLocales' => array()
        ));
    }

    /**
     * @Rest\Post("/calendar/attendees", name="post_attendees")
     * @Security("has_role('ROLE_USER')")
     * @Rest\View()
     */
    public function xhrPostAttendeesAction(Request $request)
    {
        $attendees = $request->get('attendees');
        $activityId = $request->get('activityId');
        if (!$attendees || !$activityId) {
            return ['status' => 'ko', 'message' => 'You must provide attendees object and activityId'];
        }
        if (!$this->validAttendees($attendees)) {
            return ['status' => 'ko', 'message' => 'The relations between attendees is not auhtorized'];
        }
        if (!$calL = $this->registerAttendees($attendees, $activityId)) {
            return ['status' => 'ko', 'message' => 'The registration has failed'];
        }

        return ['status' => 'ok', 'message' => 'Registration successful', 'data' => $calL];
    }

    /**
     * @Rest\Post("/calendar/attendee", name="post_attendee")
     * @Security("has_role('ROLE_USER')")
     * @Rest\View()
     */
    public function xhrPostAttendeeAction(Request $request)
    {
        $attendee = $request->get('attendee');
        if (!$attendee) {
            return ['status' => 'ko', 'message' => 'You must provide attendee object'];
        }
        if (isset($attendee['codco'])) {
            $contact = $this->calendarRepository->findContact($attendee['codco']);
        } else {
            $contact = $this->contactRepository->findContactByInfos(
                $attendee['nom'],
                $attendee['prenom'],
                $attendee['datnaiss']
            );
        }
        if ($contact === null) {
            $contact = new Contact();
        }

        $em = $this->getDoctrine()->getManager();
        $this->setContact($contact, $attendee);
        $contact = $this->setContact($contact, $attendee);
        $em->persist($contact);
        $em->flush();
        return ['status' => 'ok', 'data' => $contact];
    }


    /**
     * @Rest\Get("/calendar/attendees", name="get_attendees")
     * @Security("has_role('ROLE_USER')")
     * @Rest\View()
     */
    public function xhrGetLastAttendeesAction()
    {
        $attendees = $this->getAttendees($this->getUser());
        return ['status' => 'ok', 'data' => $attendees];
    }

    public function getCalendarAction($id)
    {
        $calendar = $this->calendarRepository->findCalendar($id);

        if (!$calendar) {
            return false;
        }
        $calendar['sitact'] = $this::SITES[array_search($calendar['sitact'], $this::SITES)]['name'];
        return $calendar;
    }

    private function registerAttendees($attendees, $activityId)
    {
        $em = $this->getDoctrine()->getManager();
        foreach ($attendees as $a) {
            if ($contact = $this->contactRepository->findContact($a['codco'])) {
                $contact = $this->setContact($contact, $a);
                $em->persist($contact);

                if ($a['coltyp'] === 'enfan' || $a['coltyp'] === 'conjo') {
                    if (!$contactl = $this->contactRepository->findContactL($contact->getCodco(), $a['colp'])) {
                        $contactl = new ContactL();
                        $contactl->setCol((int) $a['codco']);
                    }
                    $contactl->setColp((int) $a['colp'])
                    ->setColt('famil')
                    ->setColtyp($a['coltyp']);
                    $em->persist($contactl);
                }
                $calendar = $this->calendarRepository->findCalendar($activityId);
                $site = $calendar['sitact'];
                $registrationCount = (int) $this->calendarRepository->findRegistrationCount($site)['valeurn'] + 1;
                $refLcal = $this->refCal($registrationCount, $site);
                $calL = new CalL();
                $calL->setCodcal($activityId)
                ->setLcal($contact->getCodco())
                ->setTyplcal('coIns')
                ->setReflcal($refLcal)
                ->setJslcal(json_encode(['Arriv' => ['Transport' => $a['transport'], 'Memo' => $a['memo']]]));
                $em->persist($calL);
            }
        }
        $em->flush();

        $this->notifyAttendees($attendees, $refLcal, $calendar);
        return $refLcal;
    }

    private function notifyAttendees($attendees, $ref, $calendar)
    {
        $this->mailer->send(
            $this->getUser()->getEmail(),
            $this->translator->trans('calendar.notify.attendee.subject'),
            $this->renderView('emails/calendar-notify-attendees.html.twig', [
                'attendees' => $attendees, 'ref' => $ref, 'calendar' => $calendar
                ])
        );
    }

    private function setContact(Contact $contact, $attendee)
    {
        $contact->setNom($attendee['nom'])
        ->setPrenom($attendee['prenom'])
        ->setCivil($attendee['civil'])
        ->setAdresse($attendee['adresse'])
        ->setCp($attendee['cp'])
        ->setVille($attendee['ville'])
        ->setPays($attendee['pays'])
        ->setTel($attendee['tel'])
        ->setMobil($attendee['mobil'])
        ->setEmail($attendee['email'])
        ->setDatnaiss(new \DateTime($attendee['datnaiss']))
        ->setProfession($attendee['profession']);
        return $contact;
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
            if ($this->isChild($attendee['datnaiss'])) {
                if (!$this->hasParent($attendee, $attendees)) {
                    return false;
                }
            }
        }
        return true;
    }
    
    private function hasParent($child, $attendees)
    {
        if ($child['coltyp'] !== 'enfan') {
            return false;
        }
        foreach ($attendees as $attendee) {
            $adult = $this->isAdult($attendee['datnaiss']);
            if ((int) $attendee['codco'] === (int) $child['colp'] && $adult) {
                return true;
            }
        }
        return false;
    }

    private function isChild(string $datnaiss)
    {
        $datnaiss = new \DateTime($datnaiss);
        $now = new \DateTime();
        $diff = $datnaiss->diff($now);
        return ($diff->y <= $this::YEARS_CHILD);
    }
    
    private function isAdult(string $datnaiss)
    {
        $datnaiss = new \DateTime($datnaiss);
        $now = new \DateTime();
        $diff = $datnaiss->diff($now);
        return ($diff->y >= $this::YEARS_ADULT);
    }


    private function getParents(Contact $contact)
    {
        return $this->calendarRepository->findParents($contact->getCodco());
    }

    private function getAttendees(Contact $contact)
    {
        $refs = $this->calendarRepository->findRegisteredRefs($contact->getCodco());
        if ($refs === null) {
            return null;
        }
        return $this->calendarRepository->findAttendees(array_column($refs, 'reflcal'), $contact->getCodco());
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
            $id = $event['actId'];

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
            

            $retreat['id'] = $id;
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
     * @Route("/liste-retraites", name="calendar-fr")
     * @Route("/list-retreats", name="calendar-en")
     * @Route("/liste-ruckzuge", name="calendar-de")
     * @Route("/lista-ritiri", name="calendar-it")
     * @Route("/lista-retiros", name="calendar-es")
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
