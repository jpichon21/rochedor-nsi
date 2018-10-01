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
        LoggerInterface $logger,
        PaymentService $paymentService,
        Translator $translator
    ) {
        $this->tpaysRepository = $tpaysRepository;
        $this->pageService = $pageService;
        $this->em = $em;
        $this->donRepository = $donRepository;
        $this->logger = $logger;
        $this->paymentService = $paymentService;
        $this->translator = $translator;
    }

    /**
     * @Route("/{_locale}/don-ponctuel", name="giftr-fr")
     * @Route("/{_locale}/one-tiem-donation", name="giftr-en")
     * @Route("/{_locale}/einmalige-spende", name="giftr-de")
     * @Route("/{_locale}/donazione-una-tantum", name="giftr-it")
     * @Route("/{_locale}/donación-de-una-sola-vez", name="giftr-es")
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
        return $this->render('default/giftr.html.twig', [
            'page' => $page,
            'availableLocales' => array(),
            'countries' => $countriesJSON
        ]);
    }

    /**
     * @Rest\Post("/xhr/gift/create", name="post_gift_create")
     * @Security("has_role('ROLE_USER')")
     * @Rest\View()
     */
    public function xhrPostGiftCreateAction(Request $request)
    {

        $user = $this->getUser();
        $gift = $request->get('gift');
        if (!$gift) {
            return ['status' => 'ko', 'message' => 'You must provide a gift object'];
        }
        $ref = $this->getNewRef();
        $don = new Don();
        $don->setMntdon($gift['mntdon'])
        ->setContact($user)
        ->setDestdon($gift['destdon'])
        ->setModdon(($gift['moddon'] === 'PBX') ? 'CB' : 'PAYPAL')
        ->setMemodon($gift['memodon'])
        ->setRefdon($ref)
        ->setEnregdon(new \DateTime('0000-00-00 00:00:00'))
        ->setDatdon(new \DateTime())
        ->setValidDon(0)
        ->setBanqdon(9)
        ->setMondon('€');
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
            'gift'
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
        return $this->render('gift/payment-return.html.twig', [
            'status' => $status,
            'method' => $method
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
        $don = $this->donRepository->findByRef($ref);
        if ($status && (int) $don->getMntdon() === (int) $amount) {
            $don->setEnregdon(new \DateTime())
            ->setPaysdon($country)
            ->setStatus('success')
            ->setTransdon($status)
            ->setValidDon(1);
        }
        $this->em->persist($don);
        $this->em->flush();
        return new Response('ok');
    }

    private function getNewRef()
    {
        $year = date('y');
        $lastRef = $this->donRepository->findLastRef($year);
        if ($lastRef === null) {
            return $year . '-0000';
        }
        return $year . '-' . str_pad(intval(str_replace($year . '-', '', $lastRef)) + 1, 5, '0', STR_PAD_LEFT);
    }
}
