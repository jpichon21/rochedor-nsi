<?php

/*
 * (c) Logomotion <production@logomotion.fr>
 *
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Contact;
use AppBundle\Entity\DonR;
use AppBundle\Repository\DonRRepository;
use AppBundle\Service\CountryService;
use AppBundle\Service\GiftService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Repository\TpaysRepository;
use AppBundle\Service\PageService;
use AppBundle\Service\PaypalService;
use AppBundle\Entity\Don;
use AppBundle\Repository\DonRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use Psr\Log\LoggerInterface;
use AppBundle\Service\PaymentService;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface as Translator;
use SensioLabs\Security\Exception\HttpException;

class GiftController extends Controller
{
    const AUTHORIZED_PAYMENT_IP_ADRESSES = [
        '195.101.99.73',
        '195.101.99.76',
        '194.2.160.80',
        '194.2.160.82',
        '194.2.160.91',
        '195.25.67.0',
        '195.25.67.2',
        '195.25.67.11',
        '194.2.122.190',
        '195.25.67.22',
        '127.0.0.1'
    ];
    
    /**
     * @var PageService
     */
    private $pageService;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var DonRepository
     */
    private $donRepository;

    /**
     * @var DonRRepository
     */
    private $donRRepository;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var PaymentService
     */
    private $paymentService;

    /**
     * @var Translator
     */
    private $translator;

    public function __construct(
        TpaysRepository $tpaysRepository,
        PageService $pageService,
        EntityManagerInterface $em,
        DonRepository $donRepository,
        DonRRepository $donRRepository,
        LoggerInterface $logger,
        PaymentService $paymentService,
        Translator $translator
    ) {
        $this->tpaysRepository = $tpaysRepository;
        $this->pageService = $pageService;
        $this->em = $em;
        $this->donRepository = $donRepository;
        $this->donRRepository = $donRRepository;
        $this->logger = $logger;
        $this->paymentService = $paymentService;
        $this->translator = $translator;
    }

    /**
     * @Route("/{_locale}/don-ponctuel", name="gift-fr")
     * @Route("/{_locale}/one-tiem-donation", name="gift-en")
     * @Route("/{_locale}/einmalige-spende", name="gift-de")
     * @Route("/{_locale}/donazione-una-tantum", name="gift-it")
     * @Route("/{_locale}/donación-de-una-sola-vez", name="gift-es")
     */
    public function giftAction(Request $request, CountryService $countryService)
    {
        $giftData = [
            'amount' => null,
            'destDon' => null,
            'giftNote' => null,
            'modDon' => null,
        ];
        if (!is_null($request->query->get('giftData'))) {
            $giftData = $request->query->get('giftData');
        }
        $countries = $this->tpaysRepository->findAllCountry();
        list($countriesJSON, $preferredChoices) = $countryService->orderCountryListByPreference($countries);

        $page = $this->pageService->getContentFromRequest($request);
        if (!$page) {
            throw $this->createNotFoundException($this->translator->trans('global.page-not-found'));
        }

        return $this->render('default/gift.html.twig', [
            'page' => $page,
            'availableLocales' => array(),
            'countries' => $countriesJSON,
            'preferredCountries' => $preferredChoices,
            'giftData' => $giftData
        ]);
    }

    /**
     * @Rest\Post("/xhr/gift/create", name="post_gift_create")
     * @Rest\View()
     *
     * @throws \Exception
     */
    public function xhrPostGiftCreateAction(Request $request, GiftService $giftService)
    {
        $user = $this->getUser();
        $gift = $request->get('gift');
        if (!$gift) {
            return ['status' => 'ko', 'message' => 'You must provide a gift object'];
        }
        switch ($gift['moddon']) {
            // Si on paye par cheque ou virement, c'est une promesse de don -> donR
            case PaymentService::METHOD_CHEQUE:
            case PaymentService::METHOD_VIREMENT:
            case PaymentService::METHOD_VIREMENT_REGULIER:
                $don = $giftService->createDonR($gift, $user);
                break;
            // Sinon, c'est un vrai don -> don
            default:
                $don = $giftService->createDon($gift, $user);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($don);
        $em->flush();
        $paymentUrl = $this->paymentService->getUrl(
            $gift['moddon'],
            $don->getMntdon(),
            $don->getRefdon(),
            $this->translator->trans('gift.payment.title'),
            $this->getUser()->getEmail(),
            $request->getLocale(),
            'gift',
            !empty($gift['virPeriod']) ? $gift['virPeriod'] : null,
            $don->getDestdon(),
            [],
            null,
            $don instanceof Don ? $don->getMemodon() : null
        );
        if (!$paymentUrl) {
            return ['status' => 'ko', 'message' => 'an error as occured'];
        }
        return ['status' => 'ok', 'data' => $paymentUrl];
    }
    
    /**
     *
     * @Route("/{_locale}/gift/payment-return/{method}/{status}", name="gift_payment_return")
     */
    public function paymentReturnAction($method, $status, Request $request, PaypalService $paypalService)
    {
        if (null !== $request->query->get('Ref')) {
            /** @var Don $gift */
            $gift = $this
                        ->donRepository
                        ->findByRef($request->query->get('Ref'));
            /** @var Contact $contact */
            $contact = $gift->getContact();
            $parsedContact = $contact->getCivil().' '.$contact->getPrenom().' '.$contact->getNom();

            return $this->render('gift/payment-return.html.twig', [
                'name' => $parsedContact,
                'amount' => $gift->getMntdon(),
                'status' => $status,
                'method' => $method,
                'email' => $contact->getEmail(),
                'address' => $contact->getAdresse(),
                'zipcode' => $contact->getCp(),
                'city' => $contact->getVille(),
                'country' => $contact->getPays(),
                'ref' => $gift->getRefdon()
            ]);
        }
        return $this->render('gift/payment-return.html.twig', [
            'status' => $status,
            'method' => $method
        ]);
    }

    /**
     * @Route("/{_locale}/gift/payment-return-cheque", name="gift_paymentcheque_return")
     * @Security("has_role('ROLE_USER')")
     */
    public function paymentReturnChequeAction(Request $request)
    {
        return $this->render('gift/payment-return-cheque.html.twig', [
            'civility' => $request->query->get('civility'),
            'name' => $request->query->get('name'),
            'amount' => $request->query->get('amount'),
            'affectation' => $request->query->get('affectation')
        ]);
    }

    /**
     * @Route("/{_locale}/gift/payment-return-virement", name="gift_paymentvir_return")
     * @Security("has_role('ROLE_USER')")
     */
    public function paymentReturnVirementAction(Request $request)
    {
        return $this->render('gift/payment-return-virement.html.twig', [
            'regular' => false,
            'civility' => $request->query->get('civility'),
            'name' => $request->query->get('name'),
            'amount' => $request->query->get('amount')
        ]);
    }

    /**
     * @Route("/{_locale}/gift/payment-return-virement-regulier", name="gift_paymentvir_regulier_return")
     * @Security("has_role('ROLE_USER')")
     */
    public function paymentReturnVirementRegulierAction(Request $request)
    {
        return $this->render('gift/payment-return-virement.html.twig', [
            'regular' => true,
            'civility' => $request->query->get('civility'),
            'name' => $request->query->get('name'),
            'amount' => $request->query->get('amount'),
            'period' => $request->query->get('period')
        ]);
    }

    /**
     * @Route("/{_locale}/gift/payment-notify/{method}", name="gift_payment_notify")
     */
    public function paymentNotifyAction($method, Request $request, PaypalService $paypalService)
    {
        $this->logger->info($request);
        if ($method === 'paybox') {
            if (!in_array($request->getClientIp(), $this::AUTHORIZED_PAYMENT_IP_ADRESSES)) {
                $this->logger->alert('Bad IP address used for payment return: '.$request->getClientIp());
                throw new HttpException('Bad IP address used for payment return');
            }
            $ref = $request->get('Ref');
            $status = ($request->get('Erreur') === '00000') ? $request->get('Trans') : false;
            $country = $request->get('Pays');
            $amount = ($request->get('Amount')/100);
        } elseif ($method === 'paypal') {
            if ($this->getParameter('payment_env') === 'dev') {
                $paypalService->useSandbox();
            }
            if ($paypalService->verifyIPN()) {
                $ref = $request->get('item_number');
                $status = $request->get('ipn_track_id');
                $country = $request->get('residence_country');
                $amount = $request->get('mc_gross');
            } else {
                $this->logger->alert('Paypal IPN verification failed');
                throw new HttpException('Paypal IPN verification failed');
            }
        }
        /** @var Don $don */
        $don = $this->donRepository->findByRef($ref);
        if ($status && (int) $don->getMntdon() === (int) $amount) {
            $don->setDatDon(new \DateTime())
            ->setPaysdon($country)
            ->setStatus('success')
            ->setTransdon($status);

            /** @var Contact $contact */
            $contact = $don->getContact();
            $contact->setTypcoDon();
            $this->get('app.mailer')->send(
                [$contact->getEmail() => $contact->getPrenom().' '.$contact->getNom()],
                $this->get('translator')->trans('gift.title'),
                $this->container->get('templating')->render('emails/gift/gift-notify-online.html.twig', [
                    'name' => $contact->getCivil().' '.$contact->getPrenom().' '.$contact->getNom(),
                    'amount' => $don->getMntdon(),
                    'status' => $status,
                    'method' => $method,
                    'email' => $contact->getEmail(),
                    'address' => $contact->getAdresse(),
                    'zipcode' => $contact->getCp(),
                    'city' => $contact->getVille(),
                    'country' => $contact->getPays(),
                    'ref' => $don->getRefdon()
                ])
            );
        }
        $this->em->persist($don);
        $this->em->flush();
        return new Response('ok');
    }

    /**
     * Permet de déconnecter l'utilisateur avant de le rediriger sur la liste des dons possible
     *
     * @Route("/cancel-gift", name="cancel_gift")
     */
    public function cancelGiftAction(Request $request)
    {
        $session = new Session();
        $session->invalidate();

        $route = $this->get('cmf_routing.route_provider')->getRouteByName('dons');
        if (is_null($route)) {
            return $this->redirectToRoute('logout-message', [
                'from' => 'don-ponctuel'
            ]);
        }

        return $this->redirectToRoute($route, [
            $request
        ]);
    }
}
