<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Swagger\Annotations as SWG;
use AppBundle\Repository\ClientRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Translation\TranslatorInterface as Translator;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use AppBundle\Service\Mailer;
use AppBundle\Entity\Client;
use AppBundle\Form\RegisterType;
use AppBundle\Service\PageService;

class ShopSecurityController extends Controller
{

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
     * @var ClientRepository
     */
    private $clientRepository;

    public function __construct(
        Mailer $mailer,
        Translator $translator,
        PageService $pageService,
        ClientRepository $clientRepository
    ) {
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->pageService = $pageService;
        $this->clientRepository = $clientRepository;
    }

    /**
    * @Route("/shop/login", name="shop-login", requirements={"methods": "POST"})
    * @SWG\Post(
    *   path="/shop/login",
    *   summary="shop/login",
    *   @SWG\Parameter(
    *          name="body",
    *          in="body",
    *          required=true,
    *          @SWG\Schema(
    *              @SWG\Property(
    *                  property="username",
    *                  type="string"
    *              ),
    *              @SWG\Property(
    *                  property="password",
    *                  type="string"
    *              ),
    *          )
    *     ),
    *   @SWG\Response(
    *     response=201,
    *     description="not logged in"
    *   )
    * )
    */
    public function loginAction(Request $request)
    {
        /**
         * @var AppBundle\Entity\Client
         */
        $client = $this->getUser();

        if (!$client) {
            return new JsonResponse([
                'status' => 'not logged in'
            ], 201);
        }
        
        return new JsonResponse([
            'codcli' => $client->getCodcli(),
            'civil' => $client->getCivil(),
            'nom' => $client->getNom(),
            'prenom' => $client->getPrenom(),
            'rue' => $client->getRue(),
            'adresse' => $client->getAdresse(),
            'cp' => $client->getCp(),
            'ville' => $client->getVille(),
            'pays' => $client->getPays(),
            'tel' => $client->getTel(),
            'mobil' => $client->getMobil(),
            'email' => $client->getEmail(),
            'societe' => $client->getSociete(),
            'tvaintra' => $client->getTvaintra(),
            'memocli' => $client->getMemocli(),
            'enregcli' => $client->getEnregcli(),
            'conData' => $client->getConData(),
            'conNews' => $client->getConNews(),
            'dateConDonnes' => $client->getDatConDonnees(),
            'dateNewsDonnes' => $client->getDatNewsDonnees(),
            'professionnel' => $client->getProfessionnel()
        ]);
    }

    /**
    * @Route("/shop/register", name="shop-register", requirements={"methods": "POST"})
    */
    public function registerAction(
        Request $request,
        UserPasswordEncoderInterface $encoder
    ) {
        $clientReq = $request->get('client');
        if (!$clientReq) {
            return new JsonResponse(['status' => 'ko', 'message' => 'You must provide client object']);
        }
        
        if (!$this->clientRepository->isEmailUnique($clientReq['email'])) {
            return new JsonResponse(['status' => 'ko', 'message' => 'security.email_exists']);
        }

        $client = new Client();
        
        if ($clientReq['conData'] === false) {
            $rawPassword = $this->randomString(20);
            $password = $encoder->encodePassword($client, $rawPassword);
            $username = $this->randomString(20);
            $client
            ->setPassword($password)
            ->setUsername($username)
            ->setDatConDonnees(null)
            ->setEmail(null);
        } else {
            if (!$this->clientRepository->isUsernameUnique($clientReq['username'])) {
                return new JsonResponse(['status' => 'ko', 'message' => 'security.username_exists']);
            }
            if (!$this->isBadPassword($clientReq['password'])) {
                return new JsonResponse(['status' => 'ko', 'message' => 'user.security_password']);
            }
            if (!$this->isToShortPassword($clientReq['password'])) {
                return new JsonResponse(['status' => 'ko', 'message' => 'security.password_too_small']);
            }
            $password = $encoder->encodePassword($client, $clientReq['password']);
            $client
            ->setPassword($password)
            ->setUsername($clientReq['username'])
            ->setDatConDonnees(new \DateTime('now'))
            ->setEmail($clientReq['email']);
        }
        $client
        ->setCivil($clientReq['civil'])
        ->setProfessionnel($this->parsePro($clientReq['professionnel']))
        ->setNom($clientReq['nom'])
        ->setPrenom($clientReq['prenom'])
        ->setRue($clientReq['rue'])
        ->setAdresse($clientReq['adresse'])
        ->setCp($clientReq['cp'])
        ->setVille($clientReq['ville'])
        ->setPays($clientReq['pays'])
        ->setTel($clientReq['tel'])
        ->setMobil($clientReq['mobil'])
        ->setSociete($clientReq['societe'])
        ->setTvaintra($clientReq['tvaintra'])
        ->setMemocli($clientReq['memocli'])
        ->setEnregcli(new \DateTime('now'));
        if ($clientReq['conNews'] === true) {
            $client->setDatNewsDonnees(new \DateTime('now'));
        } else {
            $client->setDatNewsDonnees(null);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($client);
        $em->flush();

        return new JsonResponse([
            'codcli' => $client->getCodcli(),
            'username' => $client->getUsername(),
            'civil' => $client->getCivil(),
            'nom' => $client->getNom(),
            'prenom' => $client->getPrenom(),
            'rue' => $client->getRue(),
            'adresse' => $client->getAdresse(),
            'cp' => $client->getCp(),
            'ville' => $client->getVille(),
            'pays' => $client->getPays(),
            'tel' => $client->getTel(),
            'mobil' => $client->getMobil(),
            'email' => $client->getEmail(),
            'societe' => $client->getSociete(),
            'tvaintra' => $client->getTvaintra(),
            'memocli' => $client->getMemocli(),
            'enregcli' => $client->getEnregcli(),
            'conData' => $client->getConData(),
            'conNews' => $client->getConNews(),
            'dateConDonnes' => $client->getDatConDonnees(),
            'dateNewsDonnes' => $client->getDatNewsDonnees(),
            'professionnel' => $client->getProfessionnel()
        ]);
    }

    /**
    * @Route("/shop/checkmail", name="shop-check-mail", requirements={"methods": "POST"})
    */
    public function checkMailAction(Request $request)
    {
        $mail = $request->get('mail');
        if (!$mail) {
            return new JsonResponse(['status' => 'ko', 'message' => 'You must provide mail object']);
        }
        
        if (!$this->clientRepository->isEmailUnique($mail['email'])) {
            return new JsonResponse(['status' => 'ko', 'message' => 'security.email_exists']);
        }

        return new JsonResponse(['ok' => 'Mail Valide']);
    }

    public function randomString($length)
    {
        return substr(
            str_shuffle(
                str_repeat(
                    $x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
                    ceil($length/strlen($x))
                )
            ),
            1,
            $length
        );
    }

  /**
    * @Route("/shop/editcli", name="shop-editcli", requirements={"methods": "POST"})
    */
    public function editCliAction(
        Request $request,
        UserPasswordEncoderInterface $encoder
    ) {
        $clientReq = $request->get('client');
        if (!$clientReq) {
            return new JsonResponse(['status' => 'ko', 'message' => 'You must provide client object']);
        }

        $client = $this->clientRepository->findClient($clientReq['codcli']);
        $password = $encoder->encodePassword($client, $clientReq['password']);


        $client
        ->setCivil($clientReq['civil'])
        ->setNom($clientReq['nom'])
        ->setPrenom($clientReq['prenom'])
        ->setRue($clientReq['rue'])
        ->setAdresse($clientReq['adresse'])
        ->setCp($clientReq['cp'])
        ->setVille($clientReq['ville'])
        ->setPays($clientReq['pays'])
        ->setTel($clientReq['tel'])
        ->setMobil($clientReq['mobil'])
        ->setProfessionnel($this->parsePro($clientReq['professionnel']))
        ->setEmail($clientReq['email']);
        if ($clientReq['password'] !== '') {
            $client->setPassword($password);
        }
        $client->setSociete($clientReq['societe'])
        ->setTvaintra($clientReq['tvaintra'])
        ->setMemocli($clientReq['memocli'])
        ->setEnregcli(new \DateTime('now'));

        if ($clientReq['conNews'] === "false") {
            $client->setDatNewsDonnees(null);
        } else {
            if ($client->getDatNewsDonnees() === null) {
                $client->setDatNewsDonnees(new \DateTime('now'));
            }
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($client);
        $em->flush();

        return new JsonResponse([
            'codcli' => $client->getCodcli(),
            'civil' => $client->getCivil(),
            'nom' => $client->getNom(),
            'prenom' => $client->getPrenom(),
            'rue' => $client->getRue(),
            'adresse' => $client->getAdresse(),
            'cp' => $client->getCp(),
            'ville' => $client->getVille(),
            'pays' => $client->getPays(),
            'tel' => $client->getTel(),
            'mobil' => $client->getMobil(),
            'email' => $client->getEmail(),
            'societe' => $client->getSociete(),
            'tvaintra' => $client->getTvaintra(),
            'memocli' => $client->getMemocli(),
            'enregcli' => $client->getEnregcli(),
            'professionnel' => $client->getProfessionnel(),
            'conData' => $client->getConData(),
            'conNews' => $client->getConNews(),
            'dateConDonnes' => $client->getDatConDonnees(),
            'dateNewsDonnes' => $client->getDatNewsDonnees(),
        ]);
    }

    /**
    * @Route("/shop/password-request", name="shop-password-request", requirements={"methods": "POST"})
    */
    public function passwordRequestAction(Request $request)
    {
        $email = $request->get('email');
        $lastname = $request->get('lastname');
        $firstname = $request->get('firstname');
        if (!$email || !$lastname || !$firstname) {
            return new JsonResponse(['status' => 'ko', 'message' => 'security.password_request.missing_infos']);
        }

        if (!$clients = $this->clientRepository->findClientByInfos($email, $lastname, $firstname)) {
            return new JsonResponse(['status' => 'ko', 'message' => 'security.password_request.not_found']);
        }
        
        $expiresAt = new \DateTime();
        $expiresAt->add(new \DateInterval('PT4H'));
        foreach ($clients as $client) {
            $token = sha1(random_bytes(15));
            $client->setResetToken($token)
            ->setResetTokenExpiresAt($expiresAt);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($client);
            $em->flush();
            $link = $this->generateUrl(
                'shop-password-reset',
                array('token' => $token),
                UrlGeneratorInterface::ABSOLUTE_URL
            );
            $this->mailer->send(
                $email,
                $this->translator->trans('security.reset_password_request.subject'),
                $this->renderView(
                    'emails/security-reset-password-request-'.$request->getLocale().'.html.twig',
                    ['link' => $link, 'contact' => $client]
                )
            );
        }
        return new JsonResponse(['status' => 'ok', 'message' => 'The email has been sent']);
    }

    /**
    * @Route("/{_locale}/shop/password-reset/{token}",
    *   name="shop-password-reset",
    *   requirements={"methods": "{GET, POST}"})
    */
    public function passwordResetAction(
        Request $request,
        UserPasswordEncoderInterface $encoder,
        $token
    ) {
        $client = $this->clientRepository->findClientByToken($token);
        if (!$client) {
            return $this->render('security/password-reset.html.twig', ['client' => null]);
        }
        
        $form = $this->createFormBuilder($client)
        ->add('password', RepeatedType::class, array(
            'type' => PasswordType::class,
            'invalid_message' => 'form.message.passwords_mismatch',
            'options' => array('attr' => array('class' => 'password-field')),
            'required' => true,
            'first_options'  => array('attr' => [
            'class'=> 'input password',
            'placeholder' => 'form.label.password']
            ),
            'second_options' => array('attr' => [
            'class'=> 'input password',
            'placeholder' => 'form.label.password_repeat']
            )
        ))
        ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $encoder->encodePassword($client, $client->getPassword());
            $client->setPassword($password)
            ->setResetToken(null)
            ->setResetTokenExpiresAt(null);
            $em = $this->getDoctrine()->getManager();
            $em->persist($client);
            $em->flush();
            return $this->redirectToRoute('password-reset-success');
        }
        return $this->render('security/password-reset.html.twig', array(
            'form' => $form->createView()
        ));
    }

    private function parsePro($professionnel)
    {
        if ($professionnel === "true") {
            return true;
        }
        return false;
    }

    private function isBadPassword(string $password)
    {
        if ($password === "") {
            return false;
        }
        return true;
    }



    private function isToShortPassword(string $password)
    {
        if (strlen($password) < 8) {
            return false;
        }
        return true;
    }
}
