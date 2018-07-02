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
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use AppBundle\Service\Mailer;
use AppBundle\Entity\Client;
use AppBundle\Form\RegisterType;
use AppBundle\Service\PageService;
use FOS\RestBundle\Controller\Annotations as Rest;

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
    * @Rest\Post("/shop/login", name="shop-login")
    * @Rest\View()
    * @SWG\Post(
    *   path="/xhr/shop/login",
    *   summary="login",
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
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse([
                'status' => 'not logged in'
            ], 201);
        }

        return $user;
    }

    /**
    * @Rest\Post("/shop/register", name="register")
    * @Rest\View()
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
        
        if ($repository->findClientByEmail($clientReq['email'])) {
            return new JsonResponse(['status' => 'ko', 'message' => 'Email already in use']);
        }

        $client = new Client();
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
        ->setEmail($clientReq['email'])
        ->setPassword($password)
        ->setSociete($clientReq['societe'])
        ->setMemocli($clientReq['memocli'])
        ->setEnregcli(new \DateTime($clientReq['enregcli']));

        $em = $this->getDoctrine()->getManager();
        $em->persist($client);
        $em->flush();

        return $client;
    }

    /**
    * @Route("/password-request", name="password-request", requirements={"methods": "POST"})
    */
    public function passwordRequestAction(Request $request, ClientRepository $repository)
    {
        $email = $request->get('email');
        if (!$email) {
            return new JsonResponse(['status' => 'ko', 'message' => 'You must provide email address']);
        }
        
        if (!$client = $repository->findClientByEmail($email)) {
            return new JsonResponse(['status' => 'ko', 'message' => 'Email not found']);
        }
        $token = sha1(random_bytes(15));
        $expiresAt = new \DateTime();
        $expiresAt->add(new \DateInterval('PT4H'));
        $client->setResetToken($token)
        ->setResetTokenExpiresAt($expiresAt);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($client);
        $em->flush();
        $link = $this->generateUrl('password-reset', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);
        $this->mailer->send(
            $email,
            $this->translator->trans('security.reset_password_request.subject'),
            $this->renderView(
                'emails/security-reset-password-request-'.$request->getLocale().'.html.twig',
                ['link' => $link]
            )
        );
        return new JsonResponse(['status' => 'ok', 'message' => 'The email has been sent']);
    }
    /**
    * @Route("/password-reset/{token}", name="password-reset", requirements={"methods": "{GET, POST}"})
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
            return $this->redirectToRoute('password_reset_success');
        }
        return $this->render('security/password-reset.html.twig', array(
            'form' => $form->createView(),
            'availableLocales' => $this->pageService->getAvailableLocales('password-reset')
        ));
    }

    /**
    * @Route("/{_locale}/password-reset-success",
    * name="password-reset-success", requirements={"methods": "{GET, POST}"})
    */
    public function passwordResetSuccessAction(Request $request)
    {
        return $this->render('security/password-reset-success.html.twig', array(
            'client' => null,
            'availableLocales' => $this->pageService->getAvailableLocales('password-reset-success')
        ));
    }

    /**
    * @Route("/logout", name="logout")
    */
    public function logoutAction(Request $request)
    {
        return new JsonResponse(['status' => 'ok']);
    }
}
