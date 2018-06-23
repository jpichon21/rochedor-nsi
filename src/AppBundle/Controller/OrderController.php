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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Page;
use AppBundle\Entity\Cart;
use AppBundle\Entity\Cartline;
use AppBundle\Entity\Commande;
use AppBundle\Entity\Comprd;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Repository\CommandeRepository;
use AppBundle\Service\Mailer;

class OrderController extends Controller
{

    // /**
    //  * @var Mailer
    //  */
    // private $mailer;

    // /**
    //  * @var Translator
    //  */
    // private $translator;

    // /**
    //  * @var CommandeRepository
    //  */
    // private $commandeRepository;


    // public function __construct(
    //     CommandeRepository $commandeRepository,
    //     Mailer $mailer,
    //     Translator $translator
    // ) {
    //     $this->commandeRepository = $commandeRepository;
    //     $this->mailer = $mailer;
    //     $this->translator = $translator;
    // }
  

    /**
     * @Route("/test", requirements={"id"="\d+"})
     */
    public function test()
    {
        dump($this->generateRefCom());
        exit;
    }

    /**
     * @Rest\Post("/order/client", name="post_client")

     * @Rest\View()
    */
    public function xhrPostCliAction(Request $request)
    {
        $client = $request->get('client');
        if (!$client) {
            return ['status' => 'ko', 'message' => 'You must provide client object'];
        }
        if (!isset($client['codco'])) {
            return ['status' => 'ko', 'message' => 'You must provide a client with a codco'];
        }
        return ['status' => 'ok', 'data' => $this->getCli($client)];
    }


    /**
     * @Rest\Post("/order/delivery", name="post_delivery")

     * @Rest\View()
    */
    public function xhrPostAddrDeliveryAction(Request $request)
    {
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
            return ['status' => 'ko', 'message' => 'You must provide a client with a delivery contry'];
        }
        return ['status' => 'ok', 'data' => $delivery];
    }
    
    /**
     * @Rest\Post("/order/cart", name="post_cart")

     * @Rest\View()
    */
    public function xhrPostCartAction(Request $request)
    {

        $cart = $request->get('cart');
        if (!$cart) {
            return ['status' => 'ko', 'message' => 'You must provide cart object'];
        }
        if (!isset($cart['id'])) {
            $cart['id'] = $this->setNewCart($cart);
        }
        $this->resetCart($cart);
        
        foreach ($cart['cartlines'] as $cartline) {
            if ($this->validateCartLine($cartline)) {
                $cartlineFlushed = $cartline;
                $cartlineFlushed['id'] = $this->setNewCartline($cartline, $cart);
                $cart['cartlinesFlushed'][] = $cartlineFlushed;
            } else {
                $errors[] = $cartline;
                $cart['errors'] = $errors;
            }
        }


        if (isset($cart['errors'])) {
            foreach ($cart['cartlinesFlushed'] as $cartlineFlushed) {
                $em = $this->getDoctrine()->getManager();
                $cartlineToDelete = $this
                ->getDoctrine()->getRepository('AppBundle:Cartline')
                ->find($cartlineFlushed['id']);
                $em->remove($cartlineToDelete);
                $em->flush();
            }
            return ['status' => 'ko', 'message' => 'this cartline are not good' , 'data' => $errors ];
        }

        $em = $this->getDoctrine()->getManager();
        $cartUpdated = $em->getRepository('AppBundle:Cart')->find($cart['id']);
        $cartUpdated->setUpdated(new \Datetime('now'));
        $em->persist($cartUpdated);
        $em->flush();
        
        return ['status' => 'ok', 'data' => $cart];
    }
    
    private function resetCart($cart)
    {
        $cartlines = $this->getDoctrine()->getRepository('AppBundle:Cartline')->findByCart($cart['id']);
        $em = $this->getDoctrine()->getManager();
        foreach ($cartlines as $cartline) {
            $em->remove($cartline);
            $em->flush();
        }
    }

    private function validateCartLine($cartline)
    {
        if (!isset($cartline['codprd'])) {
            $product = null;
        } else {
            $product = $this->getDoctrine()->getRepository('AppBundle:Produit')->find($cartline['codprd']);
        }
        if (!isset($cartline['quantity'])) {
            $quantity = null;
        } else {
            $quantity = $cartline['quantity'];
        }
        if ($quantity  === null ||$quantity <= 0 || $product === null) {
            dump('jepassela');
            return false;
        }
        return true;
    }

    private function setNewCartline($cartline, $cart)
    {
        $quantity = $cartline['quantity'];
        
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AppBundle:Produit')->find($cartline['codprd']);
        $em = $this->getDoctrine()->getManager();
        $cart = $em->getRepository('AppBundle:Cart')->find($cart['id']);

        $cartline = new Cartline;
        $cartline->setCart($cart);
        $cartline->setQuantity($quantity);
        $cartline->setProduct($product);
        
        $em->persist($cartline);
        $em->flush();
        return $cartline->getId();
    }

    private function setNewCart($cart)
    {
        $updated = new \Datetime('now');
        $created = new \Datetime('now');
        if (!isset($cart['notes'])) {
            $cart['notes'] = "";
        }
        $notes = $cart['notes'];

        $em = $this->getDoctrine()->getManager();
        $cart = new Cart;
        $cart->setUpdated($updated);
        $cart->setCreated($created);
        $cart->setNotes($notes);
        
        $em->persist($cart);
        $em->flush();
        return $cart->getId();
    }
    
    /**
     * @Rest\Get("/order/cart", name="get_cart")

     * @Rest\View()
    */
    public function xhrGetCartAction(Request $request)
    {
        $cartId = $request->get('cartId');
        $em = $this->getDoctrine()->getManager();
        $cart = $em->getRepository('AppBundle:Cart')->find($cartId);
        return $cart;
    }
    /**
     * @Rest\Post("/order/paiement", name="post_paiement")

     * @Rest\View()
    */
    public function xhrPostPaimentAction(Request $request)
    {
        $paiement = $request->get('paiement');
        if (!$paiement) {
            return ['status' => 'ko', 'message' => 'You must provide a paiment object'];
        }
        if ($this->validateCartLine($cart['cartlines'])) {
            return ['status' => 'ko', 'message' => 'You must provide a client with a cart valid'];
        }
        return ['status' => 'ok', 'data' => $cart];
    }


    private function getCli($client)
    {
        $codcli = $client["codco"];
        return $codcli;
    }



    // public function xhrPostOrderAction(Request $request){
    //     $order = $request->get('order');
    //     if (!$order) {
    //         return ['status' => 'ko', 'message' => 'You must provide order object'];
    //     }
    //     if (!$this->validAttendees($order)) {
    //         return ['status' => 'ko', 'message' => 'The order not contains required elements'];
    //     }
    // }

    // private function validateOrder($order){
    //     if (!$order[''], ){

    //     }
    // }

    private function registerOrder(Request $request, $codcli, Panier $panier, Paiement $paiement)
    {
        $codcli = $request->get('CodCli');
        $datCom = new \DateTime();
        $amountHT = $request->get('AmountHT');
        $modpaie = $request->get('ModPaie');
        $modliv = $request->get('ModLiv');
        $datpaie = new \DateTime();
        $validpaie = $request->get('');
        $destliv = $request->get('destLiv');
        $adliv = $request->get('AdLiv');
        $paysliv = $request->get('PaysLiv');

        $ttc = $panier->getTtc();
        $tva = $panier->getTva();
        $poids = $this->getOrderWeight() ;
        $port = $panier->getPort();
        $promo = $panier->getPromo();

        $datliv = $paiement->getDatLiv();
        $paysip = $paiement->getPaysIP();
        $Dateenreg = $paiement->getDatEnreg();

        $em = $this->getDoctrine()->getManager();
        $command = new Commande;
        
        $command->setRefcom($this->generateRefCom());
        $command->setCodcli($codcli);
        $command->setDatcom($datCom);
        $command->setMontant($amountHT);
        $command->setModpaie($modpaie);
        $command->setModliv($modliv);
        $command->setDatpaie($datPaie);
        $command->setValidpaie($validpaie);
        $command->setDesliv($destliv);
        $command->setAdLiv($adliv);
        $command->setPaysliv($paysliv);
        $command->setTtc($ttc);
        $command->setTva($tva);
        $command->setPoids($poids);
        $command->setPort($port);
        $command->setPromo($promo);
        $command->setDatlic($datliv);
        $command->setPaysip($paysip);
        $command->setDatenreg($Dateenreg);
    
        $em->persist($command);
        $em->flush();

        $comprd = new Comprd;
        
        foreach ($panier["produits"] as $produit) {
            $codcom = $command->getCodcom();
            $codprd = $produit["cleprod"];
            $quant = $panier->getQuant();
            $prix = $panier->getPrixHT();
            $remise = $panier->getRemise();
        }
        
        return $command;
    }

    private function generateRefCom()
    {
        $em = $this->getDoctrine()->getManager();
        $refcom = $em->getRepository('AppBundle:Commande')->generateRefCom();
        // $refcom = $this->commandeRepository->generateRefCom();
        return $refcom;
    }
}
