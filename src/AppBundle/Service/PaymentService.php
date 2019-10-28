<?php
namespace AppBundle\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\RouterInterface;
use AppBundle\Repository\TpaysRepository;

class PaymentService
{
    const METHOD_CB = 'PBX';
    const METHOD_PAYPAL = 'PAYPAL';
    const METHOD_CHEQUE = 'CH';
    const METHOD_VIREMENT = 'VIR';
    const METHOD_VIREMENT_REGULIER = 'VIRREG';

    private $container;
    private $tPaysRepository;
    private $router;

    public function __construct(
        ContainerInterface $container,
        TpaysRepository $tPaysRepository,
        RouterInterface $router
    ) {
        $this->container = $container;
        $this->tPaysRepository = $tPaysRepository;
        $this->router = $router;
    }

    /**
     * Return payment url
     *
     * @param string $method PBX or PAYPAL
     * @param float $amount
     * @param integer $objectId Item Id
     * @param string $itemName
     * @param string $email customer's email address
     * @param string $locale
     * @param string $returnUrl
     * @param string $notifyUrl
     * @param string $baseRoute
     * @return string
     */
    public function getUrl(
        $method,
        $amount,
        $objectId,
        $itemName,
        $email,
        $locale,
        $baseRoute,
        $dateDebutVir = null,
        $periodVir = null
    ) {
        switch ($method) {
            case self::METHOD_PAYPAL:
                return $this->getPaypalUrl($amount, $objectId, $itemName, $email, $locale, $baseRoute);
            case self::METHOD_CHEQUE:
                return $this->getChequeUrl($objectId, $locale, $baseRoute);
            case self::METHOD_VIREMENT:
                return $this->getPrelevementUrl($objectId, $locale, $dateDebutVir);
            case self::METHOD_VIREMENT_REGULIER:
                return $this->getPrelevementRegulierUrl($objectId, $locale, $dateDebutVir, $periodVir);
            default:
                return $this->getPayboxUrl($amount, $objectId, $email, $locale, $baseRoute);
        }
    }

    private function getPayboxUrl(
        $amount,
        $objectId,
        $email,
        $locale,
        $baseRoute
    ) {
        $params = [
            'PBX_SITE' => $this->container->getParameter('paybox_site'),
            'PBX_RANG' => $this->container->getParameter('paybox_rang'),
            'PBX_IDENTIFIANT' => $this->container->getParameter('paybox_identifiant'),
            'PBX_TOTAL' => ceil($amount * 100),
            'PBX_DEVISE' => 978,
            'PBX_CMD' => $objectId,
            'PBX_PORTEUR' => $email,
            'PBX_REPONDRE_A' => $this->router->generate(
                $baseRoute . '_payment_notify',
                ['_locale' => $locale, 'method' => 'paybox'],
                RouterInterface::ABSOLUTE_URL
            ),
            'PBX_EFFECTUE' => $this->router->generate(
                $baseRoute . '_payment_return',
                ['_locale' => $locale, 'method' => 'paybox', 'status' => 'success'],
                RouterInterface::ABSOLUTE_URL
            ),
            'PBX_REFUSE' => $this->router->generate(
                $baseRoute . '_payment_return',
                ['_locale' => $locale, 'method' => 'paybox', 'status' => 'error'],
                RouterInterface::ABSOLUTE_URL
            ),
            'PBX_ANNULE' => $this->router->generate(
                $baseRoute . '_payment_return',
                ['_locale' => $locale, 'method' => 'paybox', 'status' => 'cancel'],
                RouterInterface::ABSOLUTE_URL
            ),
            'PBX_ATTENTE' => $this->router->generate(
                $baseRoute . '_payment_return',
                ['_locale' => $locale, 'method' => 'paybox', 'status' => 'waiting'],
                RouterInterface::ABSOLUTE_URL
            ),
            'PBX_RETOUR' => 'Amount:M;Ref:R;Auto:A;Erreur:E;Trans:T;Pays:I',
            'PBX_HASH' => 'SHA512',
            'PBX_TIME' => date('c'),
            'PBX_LANGUE' => $this->countryCode($this::METHOD_CB, $locale)
        ];
        $url = $this->container->getParameter('paybox_url');
        $url .= '?' . http_build_query($params);
        $key = $this->container->getParameter('paybox_key');
        $binKey = pack("H*", $key);
        $hmac = strtoupper(hash_hmac('sha512', urldecode(http_build_query($params)), $binKey));
        return $url . '&PBX_HMAC=' . $hmac;
    }

    private function getPaypalUrl(
        $amount,
        $objectId,
        $itemName,
        $email,
        $locale,
        $baseRoute
    ) {
        $params = [
            'amount' => $amount,
            'cmd' => '_xclick',
            'currency_code' => 'EUR',
            'item_name' => $itemName,
            'item_number' => $objectId,
            'rm' => 0,
            'return' => $this->router->generate(
                $baseRoute . '_payment_return',
                ['_locale' => $locale, 'method' => 'paybox', 'status' => 'success', 'Ref' => $objectId],
                RouterInterface::ABSOLUTE_URL
            ),
            'cancel_return' => $this->router->generate(
                $baseRoute . '_payment_return',
                ['_locale' => $locale, 'method' => 'paybox', 'status' => 'cancel', 'Ref' => $objectId],
                RouterInterface::ABSOLUTE_URL
            ),
            'business' => $this->container->getParameter('paypal_email'),
            'notify_url' => $this->router->generate(
                $baseRoute . '_payment_notify',
                ['_locale' => $locale, 'method' => 'paypal'],
                RouterInterface::ABSOLUTE_URL
            ),
            'email' => $email,
            'lc' => $this->countryCode($this::METHOD_PAYPAL, $locale)
        ];
        $url = $this->container->getParameter('paypal_url');
        $url .= '?' . http_build_query($params);
        return $url;
    }

    private function getChequeUrl($objectId, $locale, $baseRoute)
    {
        $url = $this->router->generate(
            $baseRoute . '_paymentcheque_return',
            ['_locale' => $locale, 'ref' => $objectId],
            RouterInterface::ABSOLUTE_URL
        );
        return $url;
    }

    private function getPrelevementUrl($objectId, $locale, $dateDebutVir)
    {
        $url = $this->router->generate(
            'gift_paymentvir_return',
            [
                '_locale' => $locale, 'ref' => $objectId, 'dateDebut' => $dateDebutVir
            ],
            RouterInterface::ABSOLUTE_URL
        );
        return $url;
    }

    private function getPrelevementRegulierUrl($objectId, $locale, $dateDebutVir, $virPeriod)
    {
        $url = $this->router->generate(
            'gift_paymentvir_regulier_return',
            [
            '_locale' => $locale, 'ref' => $objectId, 'dateDebut' => $dateDebutVir, 'period' => $virPeriod
            ],
            RouterInterface::ABSOLUTE_URL
        );
        return $url;
    }

    private function countryCode($method, $locale)
    {
        $code = $this->tPaysRepository->findCode($locale);

        if (!$code) {
            switch ($method) {
                case $this::METHOD_CB:
                    return 'FRA';
                break;
                case $this::METHOD_PAYPAL:
                    return 'FR_fr';
                break;
            }
        }
        switch ($method) {
            case $this::METHOD_CB:
                return $code['codpayspbx'];
            break;
            case $this::METHOD_PAYPAL:
                return $code['codpayspaypal'];
            break;
        }
    }
}
