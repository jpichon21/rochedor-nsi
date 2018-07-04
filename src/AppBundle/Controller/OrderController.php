<?php

/*
 * (c) Logomotion <production@logomotion.fr>
 *
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface as Translator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Cart;
use AppBundle\Entity\Cartline;
use AppBundle\Entity\Commande;
use AppBundle\Entity\Produit;
use AppBundle\Entity\Comprd;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Repository\CommandeRepository;
use AppBundle\Repository\ProductRepository;
use AppBundle\Repository\CartRepository;
use AppBundle\Repository\ShippingRepository;
use AppBundle\Repository\TpaysRepository;
use AppBundle\Service\Mailer;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use SensioLabs\Security\Exception\HttpException;
use AppBundle\Service\PaypalService;
use AppBundle\Service\PageService;

class OrderController extends Controller
{
    const TVA = 5.5;
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
    const TVASHIPMENT = 20;
    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var CommandeRepository
     */
    private $commandeRepository;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var ShippingRepository
     */
    private $shippingRepository;

        /**
     * @var CartRepository
     */
    private $cartRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(
        CommandeRepository $commandeRepository,
        ProductRepository $productRepository,
        CartRepository $cartRepository,
        ShippingRepository $shippingRepository,
        TpaysRepository $tpaysRepository,
        Mailer $mailer,
        Translator $translator,
        LoggerInterface $logger,
        EntityManagerInterface $em,
        PageService $pageService
    ) {
        $this->commandeRepository = $commandeRepository;
        $this->productRepository = $productRepository;
        $this->tpaysRepository = $tpaysRepository;
        $this->cartRepository = $cartRepository;
        $this->shippingRepository = $shippingRepository;
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->logger = $logger;
        $this->em = $em;
        $this->pageService = $pageService;
    }

    /**
     * @Rest\Get("/xhr/order/taxes/{cart_id}/{country}", name="get_taxes")
     * @Rest\View()
    */
    public function xhrGetTaxes(Request $request, $cart_id, $country)
    {
        return $this->getMyCartPrice($cart_id, $country);
    }
    
    private function getMyCartPrice($cart_id, $country)
    {
        $cart = $this->cartRepository->find($cart_id);
        $data = [];
        $data['totalPriceIT'] = 0;
        $data['totalPrice'] = 0;
        foreach ($cart->getCartlines() as $k => $cartline) {
            $product = $cartline->getProduct();
            $tax = $this->productRepository->findTax($product, $country);
            if ($tax->getRate() == 0) {
                $priceIncludeTaxes = $product->getPrix();
            } else {
                $priceIncludeTaxes = $this->getProductPrice($product, $tax->getRate());
            }
            $data['totalPrice'] = $data['totalPrice'] + $product->getPrix();
            $data['totalPriceIT'] = $data['totalPriceIT'] + $priceIncludeTaxes;
            $data['product'][$k]['codprd'] = $product->getCodprd();
            $data['product'][$k]['priceIT'] = $priceIncludeTaxes;
            $data['product'][$k]['price'] = $product->getPrix();
            $data['product'][$k]['name'] = $product->getProduitcourt();
        }
        $data['vat'] = $data['totalPriceIT'] - $data['totalPrice'];
        return $data;
    }
    
    private function getProductPrice($product, $taxrate)
    {
        return ($product->getPrix() * (1+($taxrate['rate']/100)));
    }

    /**
     * @Rest\Post("/order/delivery", name="post_delivery")

     * @Rest\View()
    */
    public function xhrPostAddrDeliveryAction(Request $request)
    {
        $cookies = $request->cookies;
        $cartId = $cookies->get('cart');
        $locale = $request->getLocale();

        $delivery = $request->get('delivery');
        if (!$delivery) {
            return ['status' => 'ko', 'message' => 'You must provide delivery object'];
        }
        if (!isset($delivery['adliv'])) {
            return ['status' => 'ko', 'message' => 'You must provide a client with a delivery Adresse'];
        }
        if (!isset($delivery['destliv'])) {
            return ['status' => 'ko', 'message' => 'You must provide a client with a delivery destination'];
        }
        if (!isset($delivery['paysliv'])) {
            return ['status' => 'ko', 'message' => 'You must provide a client with a delivery country'];
        }
        if (!isset($delivery['modpaie'])) {
            return ['status' => 'ko', 'message' => 'You must provide a client with a delivery mode paiement'];
        }
        if (!isset($delivery['modliv'])) {
            return ['status' => 'ko', 'message' => 'You must provide a client with a delivery mode livraison'];
        }
        if (!isset($delivery['validpaie'])) {
            return ['status' => 'ko', 'message' => 'You must provide a client with a delivery validpaie'];
        }
        if (!isset($delivery['datliv'])) {
            return ['status' => 'ko', 'message' => 'You must provide a client with a delivery datliv'];
        }
        if (!isset($delivery['paysip'])) {
            return ['status' => 'ko', 'message' => 'You must provide a client with a delivery paysip'];
        }
        if (!isset($delivery['dateenreg'])) {
            return ['status' => 'ko', 'message' => 'You must provide a client with a delivery dateenreg'];
        }
        if (!isset($delivery['cartId'])) {
            return ['status' => 'ko', 'message' => 'You must provide a client with a delivery cartid'];
        }
        // if ($this->registerOrder($delivery, $cartId, $locale)) {
        if ($order = $this->registerOrder($delivery, $cartId, $locale)) {
            return ['status' => 'ok'];
        }
        return ['status' => 'ko', 'message' => 'an error as occured', 'order' => $order];
    }

    /**
     *
     * @Route("/{_locale}/order/payment-return/{method}/{status}", name="order_payment_return")
     */
    public function paymentReturnAction($method, $status, Request $request)
    {
        return $this->render('order/payment-return.html.twig', ['status' => $status, 'method' => $method]);
    }

    /**
     * @Route("/{_locale}/order/payment-notify/{method}", name="order_payment_notify")
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
            $paypalService->useSandbox();
            if ($paypalService->verifyIPN()) {
                $ref = $request->get('item_number');
                $status = $request->get('ipn_track_id');
                $country = $request->get('residence_country');
                $this->logger->info($status);
            } else {
                $this->logger->alert('Paypal IPN verification failed');
                throw new HttpException('Paypal IPN verification failed');
            }
        }
        
        $order = $this->commandeRepository->findByRef($ref);
        if ($status && $order->getTtc() == $amount) {
            $order->setDatpaie(new \DateTime())
            ->setValidpaie($status)
            ->setPaysIP($country);
            $this->em->persist($order);
            $this->em->flush();
        } else {
            $order->setDatpaie(new \DateTime())
            ->setValidpaie('error')
            ->setPaysIP($country);
            $this->em->persist($order);
            $this->em->flush();
        }
        return new Response('ok');
    }

    private function registerOrder($delivery, $cartId, $locale)
    {
        
        $user = $this->getUser();
        // $em = $this->getDoctrine()->getManager();
        // $user = $this->getDoctrine()->getRepository('AppBundle:Contact')->findByCodco($codcli);
        $codcli = $user->getCodcli();
        

        $cart = $this->cartRepository->find($delivery['cartId']);
        // $cart = $this->getCart($cartId);

        $datCom = new \DateTime();
        $modpaie = $delivery['modpaie'];
        $modliv = $delivery['modliv'];
        $datpaie = new \DateTime();
        $validpaie = $delivery['validpaie'];
        $destliv = $delivery['destliv'];
        if ($destliv === "Other") {
            $adliv = $this->getAdLiv($delivery['adliv'], $user);
        } else {
            $adliv = $user->getCivil().
                    " ".
                    $user->getNom().
                    " ".
                    $user->getPrenom().
                    " ".
                    $user->getAdresse().
                    " ".
                    $user->getCp().
                    " ".
                    $user->getVille();
        }
        $paysliv = $delivery['paysliv'];
        
        if (!isset($delivery['memocmd'])) {
            $memoCmd = "";
        } else {
            $memoCmd = $delivery['memocmd'];
        }

        $weight = $this->getTotalWeight($cart);
        $portPrice = $this->shippingRepository->findGoodPort($weight, $paysliv, $destliv);
        $amountHT = $this->getTotalPrice($cart, $portPrice);
        $promo = 0;
        $priceit = $this->getPriceIT($cart, $portPrice);
        $vat = $this->getVATCost($priceit, $amountHT);

        $datliv = new \Datetime($delivery['datliv']);
        $paysip = $delivery['paysip'];
        $dateenreg = new \Datetime($delivery['dateenreg']);

        $em = $this->getDoctrine()->getManager();
        $order = new Commande;
        
        $order->setRefcom($this->commandeRepository->generateRefCom());
        $order->setCodcli($codcli);
        $order->setDatcom($datCom);
        $order->setMontant($amountHT);
        $order->setModpaie($modpaie);
        $order->setModliv($modliv);
        $order->setDatpaie($datpaie);
        $order->setValidpaie($validpaie);
        $order->setDestliv($destliv);
        $order->setAdLiv($adliv);
        $order->setPaysliv($paysliv);
        $order->setTtc($priceit);
        $order->setTva($vat);
        $order->setPoids($weight);
        $order->setPort($portPrice);
        $order->setPromo($promo);
        $order->setDatliv($datliv);
        $order->setPaysip($paysip);
        $order->setDatenreg($dateenreg);
        $order->setMemocmd($memoCmd);
    
        $em->persist($order);
        $em->flush();

        
        foreach ($cart->getCartlines() as $cartline) {
            $comprd = new Comprd;

            $codcom = $order->getCodcom();
            $codprd = $cartline->getProduct()->getCodprd();
            $quantity = $cartline->getQuantity();
            $prix = $cartline->getProduct()->getCodprd();
            $remise = 0;

            $comprd->setCodcom($codcom);
            $comprd->setCodprd($codprd);
            $comprd->setQuant($quantity);
            $comprd->setPrix($prix);
            $comprd->setRemise($remise);

            $em->persist($comprd);
            $em->flush();
        }
        $this->notifyClient($order, $locale, $user);
        return $order;
    }
    
    private function getCart($id)
    {
        $cart = $this->cartRepository->find($id);
        return $cart;
    }

    private function getTotalWeight($cart)
    {
        $totalWeight = 0;
        foreach ($cart->getCartlines() as $cartline) {
            $totalWeight = $totalWeight + $cartline->getProduct()->getPoids();
        }
        return $totalWeight;
    }

    /**
     * @Rest\Get("/order/data/{cartId}/{destliv}/{paysliv}", name="get_price")
     * @Rest\View()
     */
    public function xhrGetCartDataAction($cartId, $paysliv, $destliv)
    {
        ;
        $cart = $this->cartRepository->find($cartId);
        $weight = $this->getTotalWeight($cart);
        $portPrice = $this->shippingRepository->findGoodPort($weight, $paysliv, $destliv);
        
        $totalPrice = $this->getTotalPrice($cart, $portPrice);
        $priceit = $this->getPriceIT($cart, $portPrice);
        $vatCost = $this->getVatCost($priceit, $totalPrice);

        $data = [];
        $data['weight'] = $weight;
        $data['portprice'] = $portPrice;
        $data['totalPrice'] = $totalPrice;
        $data['priceIT'] = $priceit;
        $data['vatCost'] = $vatCost;

        return ['status' => 'ok', 'data' => $data];
    }

    private function getTotalPrice($cart, $portPrice)
    {
        $totalPrice = 0;
        foreach ($cart->getCartlines() as $cartline) {
            $totalPrice = $totalPrice + $cartline->getProduct()->getPrix() * $cartline->getQuantity();
        }
        $totalPrice = ($totalPrice/(1 + ($this::TVA/100))) + ($portPrice/(1 + ($this::TVASHIPMENT/100)));

        return $totalPrice;
    }

    private function getPriceIT($cart, $portPrice)
    {
        $priceit = 0;
        foreach ($cart->getCartlines() as $cartline) {
            $priceit = $priceit + $cartline->getProduct()->getPrix() * $cartline->getQuantity();
        }
        return $priceit + $portPrice;
    }
    
    private function getVATCost($produit, $HTprice)
    {
        $vat = $priceit - $HTprice;
        return $vat;
    }


    private function getAdLiv($adliv, $user)
    {
        $parsedAdliv =
                    $user->getCivil().
                    " ".
                    $user->getNom().
                    " ".
                    $user->getPrenom().
                    " ".
                    $adliv['adresse'].
                    " ".
                    $adliv['zipcode'].
                    " ".
                    $adliv['city'];
        return $parsedAdliv;
    }

    private function notifyClient($order, $locale, $user)
    {
        $this->mailer->send(
            [
                $user->getEmail(),
                $this->getParameter('email_from_address')
            ],
            $this->translator->trans('order.notify.client.subject'),
            $this->renderView('emails/order-notify-order-'.$locale.'.html.twig', [
                'order' => $order,
                ])
        );

        $this->mailer->send(
            $this->getParameter('email_from_address'),
            $this->translator->trans('order.notify.client.subject'),
            $this->renderView('emails/order-notify-order-ro.html.twig', [
                'order' => $order,
                'user' => $user,
                ])
        );
    }
     
    /**
     * @Route("/{_locale}/order", name="order-fr")
     */
    public function orderAction(Request $request)
    {
        $cookies = $request->cookies;
        $session = new Session();
        
        $countrys = $this->tpaysRepository->findAllCountry();
        $cartId = $session->get('cart');
        $page = $this->pageService->getContentFromRequest($request);
        $availableLocales = $this->pageService->getAvailableLocales($page);
        return $this->render('order/order.html.twig', [
            'cart' => $this->cartRepository->find($cartId),
            'page' => $page,
            'countrys' => $countrys,
            'availableLocales' => $availableLocales
        ]);
    }
}
