<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Swagger\Annotations as SWG;
use AppBundle\Repository\ContactRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Translation\TranslatorInterface as Translator;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use AppBundle\Service\Mailer;
use AppBundle\Entity\Contact;
use AppBundle\Form\RegisterType;
use AppBundle\Service\PageService;

class SecurityController extends Controller
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
    * @Route("/login", name="login", requirements={"methods": "POST"})
    * @SWG\Post(
    *   path="/login",
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
         * @var AppBundle\Entity\Contact
         */
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse([
                'status' => 'not logged in'
            ], 201);
        }
        return new JsonResponse([
            'username' => $user->getUsername(),
            'roles' => $user->getRoles(),
            'codco' => $user->getCodco(),
            'ident' => $user->getIdent(),
            'civil' => $user->getCivil(),
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'adresse' => $user->getAdresse(),
            'cp' => $user->getCp(),
            'ville' => $user->getVille(),
            'pays' => $user->getPays(),
            'tel' => $user->getTel(),
            'mobil' => $user->getMobil(),
            'email' => $user->getEmail(),
            'societe' => $user->getSociete(),
            'profession' => $user->getProfession(),
            'datnaiss' => $user->getDatnaiss()->format(\DateTime::ISO8601)
        ]);
    }

    /**
     * @Route("/_locale/login-form", name="login-form")
     */
    public function preLoginFormAction(Request $request)
    {
        $locale = $request->getLocale();
        return $this->redirectToRoute("login-form-$locale");
    }

    /**
     * @Route("/fr/login-form", name="login-form-fr", requirements={"methods": "{GET, POST}"})
     * @Route("/en/login-form", name="login-form-en", requirements={"methods": "{GET, POST}"})
     * @Route("/de/login-form", name="login-form-de", requirements={"methods": "{GET, POST}"})
     * @Route("/it/login-form", name="login-form-it", requirements={"methods": "{GET, POST}"})
     * @Route("/es/login-form", name="login-form-es", requirements={"methods": "{GET, POST}"})
     */
    public function loginFormAction(Request $request, AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
    
        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error,
            'availableLocales' => $this->pageService->getAvailableLocales('login-form')
        ));
    }

    /**
    * @Route("/register", name="register", requirements={"methods": "POST"})
    */
    public function registerAction(
        Request $request,
        ContactRepository $repository,
        UserPasswordEncoderInterface $encoder
    ) {
        $contactReq = $request->get('contact');
        if (!$contactReq) {
            return new JsonResponse(['status' => 'ko', 'message' => 'You must provide contact object']);
        }
        
        if ($repository->findContactByEmail($contactReq['email'])) {
            return new JsonResponse(['status' => 'ko', 'message' => 'Email already in use']);
        }

        $contact = new Contact();
        $password = $encoder->encodePassword($contact, $contactReq['password']);

        $contact->setNom($contactReq['nom'])
        ->setPrenom($contactReq['prenom'])
        ->setCivil($contactReq['civil'])
        ->setAdresse($contactReq['adresse'])
        ->setCp($contactReq['cp'])
        ->setVille($contactReq['ville'])
        ->setPays($contactReq['pays'])
        ->setTel($contactReq['tel'])
        ->setMobil($contactReq['mobil'])
        ->setEmail($contactReq['email'])
        ->setDatnaiss(new \DateTime($contactReq['datnaiss']))
        ->setPassword($password)
        ->setUsername($contactReq['email'])
        ->setProfession($contactReq['profession']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($contact);
        $em->flush();

        return new JsonResponse([
            'username' => $contact->getUsername(),
            'roles' => $contact->getRoles(),
            'codco' => $contact->getCodco(),
            'ident' => $contact->getIdent(),
            'nom' => $contact->getNom(),
            'prenom' => $contact->getPrenom(),
            'adresse' => $contact->getAdresse(),
            'cp' => $contact->getCp(),
            'ville' => $contact->getVille(),
            'pays' => $contact->getPays(),
            'tel' => $contact->getTel(),
            'mobil' => $contact->getMobil(),
            'email' => $contact->getEmail(),
            'societe' => $contact->getSociete(),
            'profession' => $contact->getProfession(),
            'datnaiss' => $contact->getDatnaiss()
        ]);
    }

    /**
    * @Route("/fr/register-form", name="register-form-fr", requirements={"methods": "{GET, POST}"})
    * @Route("/en/register-form", name="register-form-en", requirements={"methods": "{GET, POST}"})
    * @Route("/de/register-form", name="register-form-de", requirements={"methods": "{GET, POST}"})
    * @Route("/it/register-form", name="register-form-it", requirements={"methods": "{GET, POST}"})
    * @Route("/es/register-form", name="register-form-es", requirements={"methods": "{GET, POST}"})
    */
    public function registerFormAction(
        Request $request,
        ContactRepository $repository,
        UserPasswordEncoderInterface $encoder
    ) {
        $contact = new Contact();
        $form = $this->createForm(RegisterType::class, $contact);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $encoder->encodePassword($contact, $contact->getPassword());
            $contact->setPassword($password)
            ->setResetToken(null)
            ->setResetTokenExpiresAt(null)
            ->setUsername($contact->getEmail())
            ;
            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();
            return $this->redirectToRoute('password_register_success');
        }
        return $this->render('security/register.html.twig', array(
            'form' => $form->createView(),
            'availableLocales' => $this->pageService->getAvailableLocales('register-form')
        ));
    }

    /**
    * @Route("/password-request", name="password-request", requirements={"methods": "POST"})
    */
    public function passwordRequestAction(Request $request, ContactRepository $repository)
    {
        $email = $request->get('email');
        if (!$email) {
            return new JsonResponse(['status' => 'ko', 'message' => 'You must provide email address']);
        }
        
        if (!$contact = $repository->findContactByEmail($email)) {
            return new JsonResponse(['status' => 'ko', 'message' => 'Email not found']);
        }
        $token = sha1(random_bytes(15));
        $expiresAt = new \DateTime();
        $expiresAt->add(new \DateInterval('PT4H'));
        $contact->setResetToken($token)
        ->setResetTokenExpiresAt($expiresAt);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($contact);
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
    * @Route("/fr/password-reset/{token}", name="password-reset-fr", requirements={"methods": "{GET, POST}"})
    * @Route("/en/password-reset/{token}", name="password-reset-en", requirements={"methods": "{GET, POST}"})
    * @Route("/de/password-reset/{token}", name="password-reset-de", requirements={"methods": "{GET, POST}"})
    * @Route("/it/password-reset/{token}", name="password-reset-it", requirements={"methods": "{GET, POST}"})
    * @Route("/es/password-reset/{token}", name="password-reset-es", requirements={"methods": "{GET, POST}"})
    */
    public function passwordResetAction(
        Request $request,
        ContactRepository $repository,
        UserPasswordEncoderInterface $encoder,
        $token
    ) {
        $contact = $repository->findContactByToken($token);
        if (!$contact) {
            return $this->render('security/password-reset.html.twig', ['contact' => null]);
        }
        
        $form = $this->createFormBuilder($contact)
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
            $password = $encoder->encodePassword($contact, $contact->getPassword());
            $contact->setPassword($password)
            ->setResetToken(null)
            ->setResetTokenExpiresAt(null);
            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();
            return $this->redirectToRoute('password_reset_success');
        }
        return $this->render('security/password-reset.html.twig', array(
            'form' => $form->createView(),
            'availableLocales' => $this->pageService->getAvailableLocales('password-reset')
        ));
    }

    /**
    * @Route("/fr/password-reset-success", name="password-reset-success-fr", requirements={"methods": "{GET, POST}"})
    * @Route("/en/password-reset-success", name="password-reset-success-en", requirements={"methods": "{GET, POST}"})
    * @Route("/de/password-reset-success", name="password-reset-success-de", requirements={"methods": "{GET, POST}"})
    * @Route("/it/password-reset-success", name="password-reset-success-it", requirements={"methods": "{GET, POST}"})
    * @Route("/es/password-reset-success", name="password-reset-success-es", requirements={"methods": "{GET, POST}"})
    */
    public function passwordResetSuccessAction(Request $request)
    {
        return $this->render('security/password-reset-success.html.twig', array(
            'contact' => null,
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
