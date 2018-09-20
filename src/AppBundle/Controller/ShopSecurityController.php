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

    public function __construct(
        Mailer $mailer,
        Translator $translator,
        PageService $pageService
    ) {
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->pageService = $pageService;
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
            'enregcli' => $client->getEnregcli()
        ]);
    }

    /**
    * @Route("/shop/register", name="shop-register", requirements={"methods": "POST"})
    */
    public function registerAction(
        Request $request,
        ClientRepository $repository,
        UserPasswordEncoderInterface $encoder
    ) {
        $clientReq = $request->get('client');
        if (!$clientReq) {
            return new JsonResponse(['status' => 'ko', 'message' => 'You must provide client object']);
        }
        
        if ($repository->findClientByUsername($clientReq['username'])) {
            return new JsonResponse(['status' => 'ko', 'message' => 'security.username_exists']);
        }

        $client = new Client();
        $password = $encoder->encodePassword($client, $clientReq['password']);

        $client
        ->setCivil($clientReq['civil'])
        ->setNom($clientReq['nom'])
        ->setPrenom($clientReq['prenom'])
        ->setUsername($clientReq['username'])
        ->setRue($clientReq['rue'])
        ->setAdresse($clientReq['adresse'])
        ->setCp($clientReq['cp'])
        ->setVille($clientReq['ville'])
        ->setPays($clientReq['pays'])
        ->setTel($clientReq['tel'])
        ->setMobil($clientReq['mobil'])
        ->setEmail($clientReq['email'])
        ->setPassword($password)
        ->setSociete($clientReq['societe'])
        ->setTvaintra($clientReq['tvaintra'])
        ->setMemocli($clientReq['memocli'])
        ->setEnregcli(new \DateTime('now'));

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
            'enregcli' => $client->getEnregcli()
        ]);
    }

  /**
    * @Route("/shop/editcli", name="shop-editcli", requirements={"methods": "POST"})
    */
    public function editCliAction(
        Request $request,
        ClientRepository $repository,
        UserPasswordEncoderInterface $encoder
    ) {
        $clientReq = $request->get('client');
        if (!$clientReq) {
            return new JsonResponse(['status' => 'ko', 'message' => 'You must provide client object']);
        }
        
        $client = $repository->findClient($clientReq['codcli']);
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
        ->setEmail($clientReq['email']);
        if ($clientReq['password'] !== '') {
            $client->setPassword($password);
        }
        $client->setSociete($clientReq['societe'])
        ->setTvaintra($clientReq['tvaintra'])
        ->setMemocli($clientReq['memocli'])
        ->setEnregcli(new \DateTime('now'));

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
            'enregcli' => $client->getEnregcli()
        ]);
    }

    /**
    * @Route("/shop/password-request", name="shop-password-request", requirements={"methods": "POST"})
    */
    public function passwordRequestAction(Request $request, ClientRepository $repository)
    {
        $email = $request->get('email');
        $lastname = $request->get('lastname');
        $firstname = $request->get('firstname');
        if (!$email || !$lastname || !$firstname) {
            return new JsonResponse(['status' => 'ko', 'message' => 'security.password_request.missing_infos']);
        }

        if (!$clients = $repository->findClientByInfos($email, $lastname, $firstname)) {
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
        ClientRepository $repository,
        UserPasswordEncoderInterface $encoder,
        $token
    ) {
        $client = $repository->findClientByToken($token);
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
}
