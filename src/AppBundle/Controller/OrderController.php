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
use AppBundle\Entity\Commande;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Repository\ContactRepository;
use AppBundle\Service\Mailer;

class OrderController extends Controller
{
    public function setOrderData(Request $request, Panier $panier, Paiement $paiement)
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
        
        $command->setCodCom($this->generateId());
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
    }

    private function generateId()
    {
        $em = $this->getDoctrine()->getManager();
        $id= $this->getDoctrine()->getRepository('AppBundle:Commande')->generateId();
        return $id;
    }
}
