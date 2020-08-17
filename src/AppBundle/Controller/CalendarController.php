<?php

/*
 * (c) Logomotion <production@logomotion.fr>
 *
 */

namespace AppBundle\Controller;

use AppBundle\Service\CountryService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Repository\CalendarRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface as Translator;
use AppBundle\Entity\Contact;
use AppBundle\Entity\ContactL;
use AppBundle\Entity\CalL;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Page;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Repository\ContactRepository;
use AppBundle\Repository\TpaysRepository;
use AppBundle\Service\Mailer;
use AppBundle\Service\PageService;
use AppBundle\Service\ContactService;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/{_locale}")
 */
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
            "color" => "#55C055"
        ],
        [
            "sitac" => "Font",
            "value" => "font",
            "name" => "Les Fontanilles",
            "abbr" => "FT",
            "color" => "#FFA500"
        ]
    ];

    const COLORS = [
        "F" => "#E10076",
        "A" => "#55C055",
        "W" => "#00B6E8",
        "TP" => "#FFA500",
        "B" => "#418ef4",
        "RI" => "#008000",
        "FA" => "#8833CC",
        "P" => "#e541f4",
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

    /**
     * @var PageService
     */
    private $pageService;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(
        CalendarRepository $calendarRepository,
        ContactRepository $contactRepository,
        TpaysRepository $tpaysRepository,
        Mailer $mailer,
        Translator $translator,
        PageService $pageService,
        UserPasswordEncoderInterface $encoder
    ) {
        $this->calendarRepository = $calendarRepository;
        $this->contactRepository = $contactRepository;
        $this->tpaysRepository = $tpaysRepository;
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->pageService = $pageService;
        $this->encoder = $encoder;
    }

     /**
     * @Route("/inscription-retraite", name="register_calendar-fr")
     * @Route("/registration-retreat", name="register_calendar-en")
     * @Route("/anmeldung-ruhestand", name="register_calendar-de")
     * @Route("/iscrizione-ritiro", name="register_calendar-it")
     * @Route("/registro-jubilado", name="register_calendar-es")
     */
    public function calendarRegistrationAction(Request $request, CountryService $countryService)
    {
        $id = $request->query->get('id');
        $calendarURL = $this->generateUrl('calendar-'.$request->getLocale());

        $page = $this->pageService->getContentFromRequest($request);
        if (!$page) {
            throw $this->createNotFoundException($this->translator->trans('global.page-not-found'));
        }
        $availableLocales = $this->pageService->getAvailableLocales($page);

        $countries = $this->tpaysRepository->findAllCountry();
        list($countriesJSON, $preferredChoices) = $countryService->orderCountryListByPreference($countries);

        if ($id) {
            $activity = $this->getCalendarAction($id);
            if ($activity) {
                $datenow = new \DateTime();
                $datefin = $activity['datfin'];
                if ($datenow < $datefin) {
                    $activity['idact'] = intval($id);
                    return $this->render('default/calendar-registration.html.twig', [
                        'page' => $page,
                        'activity' => $activity,
                        'countries' => $countriesJSON,
                        'preferredCountries' => $preferredChoices,
                        'availableLocales' => $availableLocales
                    ]);
                }
                return $this->render('default/calendar-registration-error.html.twig', [
                    'page' => array(
                        'title' => $page->getTitle(),
                        'subTitle' => $page->getSubTitle(),
                        'content' => array('intro' => $this->translator->trans('calendar.registration.error.date'))
                    ),
                    'availableLocales' => $availableLocales
                ]);
            }
        }
        return $this->render('default/calendar-registration-error.html.twig', [
            'page' => array(
                'title' => $page->getTitle(),
                'subTitle' => $page->getSubTitle(),
                'content' => array('intro' => $this->translator->trans('calendar.registration.error.empty'))
            ),
            'availableLocales' => $availableLocales
        ]);
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
        $existingRef = $request->get('existingRef');
        if (!$attendees || !$activityId) {
            return ['status' => 'ko', 'message' => 'You must provide attendees object and activityId'];
        }
        if (!$this->validAttendees($attendees)) {
            return ['status' => 'ko', 'message' => 'Missing major tutor for a child'];
        }

        if (!$calL = $this->registerAttendees($request, $attendees, $activityId, $existingRef)) {
            return ['status' => 'ko', 'message' => 'The registration has failed'];
        }

        return ['status' => 'ok', 'message' => 'Registration successful', 'data' => $calL];
    }

    /**
     * @Rest\Post("/calendar/attendee", name="post_attendee")
     * @Security("has_role('ROLE_USER')")
     * @Rest\View()
     */
    public function xhrPostAttendeeAction(Request $request, ContactService $contactService)
    {
        $attendee = $request->get('attendee');

        if (!$attendee) {
            return ['status' => 'ko', 'message' => 'You must provide attendee object'];
        }
        
        if (isset($attendee['codco'])) {
            $contact = $this->calendarRepository->findContact($attendee['codco']);
        }
        if (!isset($contact)) {
            $contact = new Contact();
        }
        if (isset($attendee['username'])) {
            if (!$this->contactRepository->isUsernameUnique($attendee['username'], $contact->getCodco())) {
                return ['status' => 'ko', 'message' => 'security.username_exists'];
            }
        }



        $em = $this->getDoctrine()->getManager();
        $this->setContact($contact, $attendee);
        $contact = $this->setContact($contact, $attendee);
        $duplicated = $contactService->queryDuplicate($contact->getCodco());
        if ($duplicated === true) {
            $contact->setNewFich(false);
        }
        $em->persist($contact);
        $em->flush();
        return ['status' => 'ok', 'data' => $contact];
    }


    /**
     * @Rest\Get("/calendar/attendees", name="get_attendees")
     * @Security("has_role('ROLE_USER')")
     * @Rest\View()
     */
    public function xhrGetLastAttendeesAction(Request $request)
    {
        /** @var Contact $contact */
        $contact = $this->getUser();
        $attendees = $this->getParents($contact);
        $activityId = $request->query->get('activityId');
        list($alreadyRegistered, $user, $refLcal) = $this->getAlreadyRegisteredContacts($activityId);
        return ['status' => 'ok', 'data' => [
            'attendees' => $attendees,
            'alreadyRegistered' => $alreadyRegistered,
            'alreadyRegisteredYou' => $user,
            'alreadyRegisteredRef' => $refLcal
        ]];
    }

    private function getAlreadyRegisteredContacts($activityId)
    {
        /** @var CalL $registeredContact */
        $currentUser = $this->getUser();
        $calendarLRepository = $this->getDoctrine()->getManager()->getRepository(CalL::class);
        $currentUserId = $currentUser->getCodco();
        $registeredContact = $calendarLRepository->findOneBy([
            'lcal' => $currentUserId,
            'codcal' => $activityId
        ]);

        // Si on trouve une demande d'inscription déjà existante pour cet utilisateur
        $contacts = [];
        $user = null;
        $refLCal = '';
        if (!empty($registeredContact)) {
            $refLCal = $registeredContact->getRefLCal();
            $addedContacts = $calendarLRepository->findBy(['reflcal' => $refLCal]);
            /** @var CalL $addedContact */
            foreach ($addedContacts as $addedContact) {
                $contact = $this->contactRepository->findContact($addedContact->getLcal());
                if ($currentUser === $contact) {
                    $contact->setJsco($addedContact->getJslcal());
                    $user = $contact;
                } else {
                    $contactL = $this->contactRepository->findContactL($addedContact->getLcal(), $currentUserId);
                    $colTyp = $addedContact->getTyplcal();
                    $colP = $currentUser->getCodCo();
                    if ($contactL) {
                        $colTyp = $contactL->getColTyp();
                        $colP = $contactL->getColP();
                    }
                    $contact->setJsco($addedContact->getJslcal());
                    $contact->coltyp = $colTyp;
                    $contact->colp = $colP;
                    $contacts[] = $contact;
                }
            }
        }

        return [$contacts, $user, $refLCal];
    }

    public function getCalendarAction($id)
    {
        $calendar = $this->calendarRepository->findCalendar($id);

        if (!$calendar) {
            return false;
        }
        $calendar['sitact'] = $this::SITES[
            array_search($calendar['sitact'], array_column($this::SITES, 'sitac'))
            ]['name'];
        return $calendar;
    }
    
    private function registerAttendees(Request $request, $attendees, $activityId, $existingRef = '')
    {
        $em = $this->getDoctrine()->getManager();
        $calendar = $this->calendarRepository->findCalendar($activityId);
        if (empty($existingRef)) {
            $site = $calendar['sitact'];
            $registrationCount = (int) $this->calendarRepository->findRegistrationCount($site)['valeurn'] + 1;
            list($refLcal, $registrationCount) = $this->refCal($registrationCount, $site);
            $this->calendarRepository->updateRegistrationCounter($site, $registrationCount);
        } else {
            $refLcal = $existingRef;

            // Si on a une ref transmise, c'est qu'on veut modifier une inscription
            $calendarLRepository = $this->getDoctrine()->getManager()->getRepository(CalL::class);
            $registered = $calendarLRepository->findBy(['reflcal' => $existingRef]);
            foreach ($registered as $oneRegistered) {
                $this->getDoctrine()->getManager()->remove($oneRegistered);
            }
        }
        foreach ($attendees as $attendee) {
            if ($contact = $this->contactRepository->findContact($attendee['codco'])) {
                if (isset($attendee['username'])) {
                    if (!$this->contactRepository->isUsernameUnique($attendee['username'], $attendee['codco'])) {
                        return ['status' => 'ko', 'message' => 'security.username_exists'];
                    }
                }
                $contact = $this->setContact($contact, $attendee);
                $em->persist($contact);

                if ($attendee['coltyp'] === 'enfan' ||
                    $attendee['coltyp'] === 'conjo' ||
                    $attendee['coltyp'] === 'paren'
                ) {
                    if (!$contactl = $this->contactRepository->findContactL($contact->getCodco(), $attendee['colp'])) {
                        $contactl = new ContactL();
                        $contactl->setCol((int) $attendee['codco']);
                    }
                    if ($attendee['coltyp'] === 'paren') {
                        $contactl->setColp((int) $attendee['codco']);
                    } else {
                        $contactl->setColp((int) $attendee['colp']);
                    }
                    $contactl->setColt('famil')
                    ->setColrel(1)
                    ->setColtyp($attendee['coltyp']);
                    $em->persist($contactl);
                }
                if ($attendee['coltyp'] === 'accom') {
                    $contactl = new ContactL();
                    $contactl->setCol((int) $attendee['codco'])
                    ->setColp((int) $attendee['colp'])
                    ->setColt('accom')
                    ->setColrel(1)
                    ->setColtyp($attendee['coltyp']);
                    $em->persist($contactl);
                }

                if ($attendee['aut16'] == 1) {
                    $contact->setDataut16(new \DateTime($attendee['datAut16']));
                    $em->persist($contact);
                }
                $calL = new CalL();
                $calL->setCodcal($activityId)
                ->setLcal($contact->getCodco())
                ->setTyplcal(CalL::TYP_LCAL_PARTICIPANT)
                ->setReflcal($refLcal)
                ->setJslcal(json_encode(
                    [
                        'Arriv' => [
                            'Transport' => $attendee['transport'],
                            'Lieu' => ucwords($attendee['lieu']),
                            'Heure' => ($attendee['arriv'] !== '') ? explode(':', $attendee['arriv'])[0]: '',
                            'Mn' => ($attendee['arriv'] !== '') ? explode(':', $attendee['arriv'])[1]: '',
                            'Memo' => $attendee['memo']
                        ]
                    ]
                ));
                $em->persist($calL);
            }
        }
        $em->flush();

        $this->notifyAttendees($request, $attendees, $refLcal, $calendar);
        return $refLcal;
    }

    private function notifyAttendees(Request $request, $attendees, $ref, $calendar)
    {
        $this->get('logger')->err($request->getLocale());
        $this->get('logger')->err($request->getDefaultLocale());
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
        ->setNewFich(($attendee['newfich'] === 'true'))
        ->setCivil($attendee['civil'])
        ->setAdresse($attendee['adresse'])
        ->setCp($attendee['cp'])
        ->setVille($attendee['ville'])
        ->setPays($attendee['pays'])
        ->setTel($attendee['tel'])
        ->setMobil($attendee['mobil'])
        ->setEmail($attendee['email'])
        ->setDatnaiss(new \DateTime($attendee['datnaiss']))
        ->setProfession($attendee['profession'])
        ->setAut16($attendee['aut16']);
        if (isset($attendee['username'])) {
            $contact->setUsername($attendee['username']);
        }
        if (isset($attendee['colp'])) {
            $contact->colp = $attendee['colp'];
        }
        if (isset($attendee['coltyp'])) {
            $contact->coltyp = $attendee['coltyp'];
        }

        if (empty($contact->getPassword())) {
            $password = array_key_exists('password', $attendee) && $attendee['password'] !== ''
                ? $attendee['password']
                : $this->randomPassword(8);
            if ($password) {
                $passwordEncoded = $this->encoder->encodePassword($contact, $password);
                $contact->setPassword($passwordEncoded);
            }
        }

        return $contact;
    }

    private function randomPassword($length)
    {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;

        for ($i = 0; $i < $length; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }

    private function refCal($count, $site)
    {
        $now = new \DateTime();
        $ref = strtoupper(substr($site, 0, 1));
        $ref .= $now->format('y');
        $ref .= '-'.str_pad($count, 5, '0', STR_PAD_LEFT);

        // S'assure que la référence n'existe pas déjà
        $existingRef = $this->getDoctrine()->getManager()->getRepository(CalL::class)->findBy(['reflcal' => $ref]);
        if (!empty($existingRef)) {
            return $this->refCal($count + 1, $site);
        }

        return array($ref, $count);
    }
    
    private function hasParent($child, $attendees)
    {
        if ($child['coltyp'] !== 'enfan') {
            if (isset($child['autpar'])) {
                return true;
            }
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
    
    private function validAttendees($attendees)
    {
        foreach ($attendees as $attendee) {
            if ($this->isChild($attendee['datnaiss'])) {
                if (!$this->isWithAdult($attendee, $attendees)) {
                    return false;
                }
            }
        }
        return true;
    }
    
    private function isWithAdult($child, $attendees)
    {
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
        return $this->contactRepository->findParents($contact->getCodco());
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

        // Ajout de la mention 'Père'
        foreach ($speakers as &$speaker) {
            if ($speaker['civil'] === 'Père') {
                $speaker['name'] .= ' (' . $speaker['civil'] . ')';
            }
        }

        $data['sites'] = $this::SITES;
        $data['types'] = $eventTypes;
        $data['speakers'] = $speakers;
        $data['translations'] = $translations;

        return $data;
    }

    public function getRetreatsData(CalendarRepository $calendarRepo)
    {
        $retreatData = array();
        $events = $calendarRepo->findEvents();
        
        foreach ($events as $event) {
            $id = $event['codcal'];

            $dateIn = $event['dateIn'];
            $dateInParse = $event['dateIn']->format('Ymd');
            
            $dateOut = $event['dateOut'];
            $dateOutParse = $event['dateOut']->format('Ymd');

            $duration = date_diff($dateIn, $dateOut);
            $duration = substr($duration->format('%R%d'), 1);
            $duration == 0 ? $duration = 1 : $duration;
            $nameEvent = $event['event'];
            
            $speakers = array();
            foreach (explode("|", $event['speakers']) as $speaker) {
                $speakerParse = array();
                $speaker = explode(" , ", $speaker);
                if (count($speaker) > 1) {
                    $speakerParse['name'] = $speaker[0];
                    $speakerParse['value'] = $speaker[1];
                    $speakers[] = $speakerParse;
                }
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
                $site['name'] = $this::SITES[0]['name'];
                $site['color'] = $this::SITES[0]['color'];
                $site['value'] = $this::SITES[0]['value'];
            } else {
                $site['abbr'] = $this::SITES[1]['abbr'];
                $site['name'] = $this::SITES[1]['name'];
                $site['color'] = $this::SITES[1]['color'];
                $site['value'] = $this::SITES[1]['value'];
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
     * @Route("/choix-site", name="calendar-choice-site-fr")
     * @Route("/choice-place", name="calendar-choice-site-en")
     * @Route("/wahl-platz", name="calendar-choice-site-de")
     * @Route("/scelta-luogo", name="calendar-choice-site-it")
     * @Route("/opcion-lugar", name="calendar-choice-site-es")
     */
    public function calendarChoiceSiteAction()
    {
        return $this->render('default/calendar-choice-site.html.twig');
    }

    /**
     * @Route("/liste-retraites", name="calendar-fr")
     * @Route("/list-retreats", name="calendar-en")
     * @Route("/liste-ruckzuge", name="calendar-de")
     * @Route("/lista-ritiri", name="calendar-it")
     * @Route("/lista-retiros", name="calendar-es")
     */
    public function calendarAction(CalendarRepository $calendarRepo, Request $request)
    {
        $filters = $this->getDataCalendarAction($calendarRepo);
        $retreatsData = $this->getRetreatsData($calendarRepo);

        $page = $this->pageService->getContentFromRequest($request);
        if (!$page) {
            throw $this->createNotFoundException($this->translator->trans('global.page-not-found'));
        }

        // Sélection par défaut d'un site (lrdo ou font)
        $choicedSite = $request->query->get('site');
        if ($choicedSite) {
            foreach ($filters['sites'] as &$site) {
                if ($choicedSite === $site['value']) {
                    $site['selected'] = true;
                }
            }
        }

        $translationsTitle = [];
        foreach ($filters['translations'] as $translation) {
            $translationsTitle[$translation['value']] = $this->get('translator')
                ->trans('calendar.translation.title', ['%translation%' => strtolower($translation['name'])]);
        }

        $availableLocales = $this->pageService->getAvailableLocales($page);
        return $this->render('default/calendar.html.twig', [
                'page' => $page,
                'sites' => $filters['sites'],
                'types' => $filters['types'],
                'speakers' => $filters['speakers'],
                'translations' => $filters['translations'],
                'translationsTitle' => json_encode($translationsTitle),
                'retreatsData' => json_encode($retreatsData),
                'availableLocales' => $availableLocales,
                'noRetreatsMessage' => $this->translator->trans('calendar.no_retreat')
            ]);
    }

    /**
     * Permet de déconnecter l'utilisateur avant de le rediriger sur la liste des retraites
     *
     * @Route("/cancel-registration", name="cancel_registration")
     */
    public function cancelRegistrationAction(CalendarRepository $calendarRepo, Request $request)
    {
        $session = new Session();
        $session->invalidate();

        return $this->redirectToRoute('calendar-' . $request->getLocale(), [
            $calendarRepo,
            $request
        ]);
    }
}
