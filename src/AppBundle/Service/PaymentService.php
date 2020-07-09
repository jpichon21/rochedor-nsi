<?php

namespace AppBundle\Service;

use AppBundle\Entity\Commande;
use AppBundle\Entity\Contact;
use AppBundle\Entity\Tpays;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\RouterInterface;
use AppBundle\Repository\TpaysRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Twig\Error\Error;

class PaymentService
{
    const METHOD_CB = 'CB';
    const METHOD_PAYPAL = 'PP';
    const METHOD_CHEQUE = 'ICH';
    const METHOD_VIREMENT = 'IVIP';
    const METHOD_VIREMENT_REGULIER = 'IVIR';

    private $container;
    private $tPaysRepository;
    private $router;
    private $tokenStorage;
    private $entityManager;
    private $mailer;
    private $translator;

    public function __construct(
        ContainerInterface $container,
        TpaysRepository $tPaysRepository,
        RouterInterface $router,
        TokenStorageInterface $tokenStorage,
        EntityManagerInterface $entityManager,
        Mailer $mailer,
        TranslatorInterface $translator
    ) {
        $this->container = $container;
        $this->tPaysRepository = $tPaysRepository;
        $this->router = $router;
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
        $this->translator = $translator;
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
     * @param string|null $periodVir
     * @param string|null $destDon
     * @param array $delivery
     * @param Commande|null $order
     *
     * @throws Error
     *
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
        $periodVir = null,
        $destDon = null,
        $delivery = [],
        Commande $order = null,
        $memoDon = null
    ) {
        switch ($method) {
            case self::METHOD_PAYPAL:
                return $this->getPaypalUrl(
                    $amount,
                    $objectId,
                    $itemName,
                    $email,
                    $locale,
                    $baseRoute,
                    $destDon,
                    $memoDon
                );
            case self::METHOD_CHEQUE:
                if ($baseRoute === 'gift' && empty($delivery)) {
                    $contact = $this->getContact();

                    $this->sendValidationMailGift(
                        $contact,
                        $destDon,
                        $amount,
                        'emails/gift/gift-notify-cheque.html.twig',
                        $this->translator->trans('payment.cheque')
                    );
                    return $this->getChequeUrlGift($objectId, $locale, $baseRoute, $amount, $contact, $destDon);
                } else {
                    return $this->getChequeUrlOrder($objectId, $locale, $baseRoute, $amount, $delivery, $destDon);
                }
            case self::METHOD_VIREMENT:
                $contact = $this->getContact();
                $this->sendValidationMailGift(
                    $contact,
                    $destDon,
                    $amount,
                    'emails/gift/gift-notify-virement.html.twig',
                    $this->translator->trans('payment.virement')
                );

                return $this->getVirementUrl($objectId, $locale, $amount, $contact);
            case self::METHOD_VIREMENT_REGULIER:
                $contact = $this->getContact();
                $this->sendValidationMailGift(
                    $contact,
                    $destDon,
                    $amount,
                    'emails/gift/gift-notify-virement-regular.html.twig',
                    $this->translator->trans('payment.virement_reg'),
                    $periodVir
                );

                return $this->getVirementRegulierUrl($objectId, $locale, $amount, $periodVir, $contact);
            default:
                return $this->getPayboxUrl($amount, $objectId, $email, $locale, $baseRoute, $destDon, $memoDon);
        }
    }

    private function getPayboxUrl(
        $amount,
        $objectId,
        $email,
        $locale,
        $baseRoute,
        $destDon,
        $memoDon
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
                'gift-' . $locale,
                [],
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
        $baseRoute,
        $destDon,
        $memoDon
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
                ['_locale' => $locale, 'method' => 'paypal', 'status' => 'success', 'Ref' => $objectId],
                RouterInterface::ABSOLUTE_URL
            ),
            'cancel_return' => $this->router->generate(
                'gift-' . $locale,
                ['giftData' => [
                    'amount' => $amount,
                    'destDon' => $destDon,
                    'giftNote' => $memoDon,
                    'modDon' => 'PP',
                    ],
                ],
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

    private function getChequeUrlGift($objectId, $locale, $baseRoute, $amount, Contact $contact, $destDon)
    {
        $url = $this->router->generate(
            $baseRoute . '_paymentcheque_return',
            [
                '_locale' => $locale,
                'ref' => $objectId,
                'amount' => $amount,
                'civility' => $contact->getCivil(),
                'name' => $contact->getNom(),
                'affectation' => $this->translator->trans('select.allocation.'.strtolower($destDon)),
            ],
            RouterInterface::ABSOLUTE_URL
        );
        return $url;
    }

    private function getChequeUrlOrder($objectId, $locale, $baseRoute, $amount, $delivery, $destDon)
    {
        $url = $this->router->generate(
            $baseRoute . '_paymentcheque_return',
            [
                '_locale' => $locale,
                'ref' => $objectId,
                'amount' => $amount,
                'civility' => $delivery['civil'],
                'name' => $delivery['nom'],
                'affectation' => $this->translator->trans('select.allocation.'.strtolower($destDon)),
            ],
            RouterInterface::ABSOLUTE_URL
        );
        return $url;
    }

    private function getVirementUrl($objectId, $locale, $amount, Contact $contact)
    {
        $url = $this->router->generate(
            'gift_paymentvir_return',
            [
                '_locale' => $locale,
                'ref' => $objectId,
                'amount' => $amount,
                'civility' => $contact->getCivil(),
                'name' => $contact->getNom()
            ],
            RouterInterface::ABSOLUTE_URL
        );
        return $url;
    }

    private function getVirementRegulierUrl($objectId, $locale, $amount, $virPeriod, Contact $contact)
    {
        $url = $this->router->generate(
            'gift_paymentvir_regulier_return',
            [
                '_locale' => $locale,
                'ref' => $objectId,
                'amount' => $amount,
                'civility' => $contact->getCivil(),
                'name' => $contact->getNom(),
                'period' => $virPeriod
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

    /**
     * @return Contact|object|null
     */
    private function getContact()
    {
        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();

        return $this->entityManager->getRepository(Contact::class)->findOneBy([
            'username' => $user->getUsername()
        ]);
    }

    /**
     * @param Contact $contact
     * @param $destDon
     * @param $amount
     * @param $template
     * @param $subject
     * @param null $periodVir
     *
     * @throws Error
     */
    private function sendValidationMailGift(Contact $contact, $destDon, $amount, $template, $subject, $periodVir = null)
    {
        $bankName = $this->container->getParameter('bank_name.'.$destDon);
        $bankAccount = $this->container->getParameter('bank_account.'.$destDon);
        $bankIban = $this->container->getParameter('bank_iban.'.$destDon);
        $bankBic = $this->container->getParameter('bank_bic.'.$destDon);

        $this->mailer->send(
            [$contact->getEmail() => $contact->getPrenom().' '.$contact->getNom()],
            $subject,
            $this->container->get('templating')->render($template, [
                'amount' => $amount,
                'civility' => $contact->getCivil(),
                'name' => $contact->getNom(),
                'affectation' => $this->translator->trans('select.allocation.'.strtolower($destDon)),
                'bank_name' => $bankName,
                'bank_account' => $bankAccount,
                'bank_iban' => $bankIban,
                'bank_bic' => $bankBic,
                'period' => $periodVir
            ])
        );
    }
}
