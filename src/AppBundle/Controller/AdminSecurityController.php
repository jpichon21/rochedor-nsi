<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\Role\RoleHierarchy;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Translation\TranslatorInterface as Translator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use AppBundle\Repository\UserRepository;
use AppBundle\Service\Mailer;
use AppBundle\Entity\User;

class AdminSecurityController extends Controller
{
    private $mailer;
    private $translator;
    protected $serializer;
    protected $roleHierarchy;

    public function __construct(
        Mailer $mailer,
        Translator $translator,
        SerializerInterface $serializer,
        RoleHierarchyInterface $roleHierarchy
    ) {
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->serializer = $serializer;
        $this->roleHierarchy = $roleHierarchy;
    }

    /**
    * @Route("/api/login", name="admin-login", requirements={"methods": "POST"})
    */
    public function loginAction(Request $request)
    {
        /**
         * @var AppBundle\Entity\User
         */
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse([
                'status' => 'not logged in'
            ], 201);
        }

        if (!$user->getActive()) {
            return new JsonResponse([
                'error' => 'not activated'
            ], 401);
        }

        /** @var Serializer $serializer */
        $serializer = $this->get('serializer');
        return new JsonResponse(array_merge($serializer->normalize($user), ['roles' => $this->getRoles($user)]));
    }

    /**
     * @param User $user
     * @return array
     */
    protected function getRoles(User $user)
    {
        return array_map(
            function (Role $role) {
                return $role->getRole();
            },
            $this->roleHierarchy->getReachableRoles(array_map(function ($rawle) {
                return new Role($rawle);
            }, $user->getRoles()))
        );
    }

    /**
     * @Route("/admin/login-form", name="admin-login-form", requirements={"methods": "{GET, POST}"})
     */
    public function loginFormAction(Request $request, AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
    
        return $this->render('security/admin-login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error
        ));
    }


    /**
    * @Route("/api/user-update", name="admin-user-update", requirements={"methods": "PUT"})
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function updateAction(
        Request $request,
        UserPasswordEncoderInterface $encoder
    ) {
        $user = $this->getUser();
        $userReq = $request->get('user');
        
        if (!$userReq) {
            return new JsonResponse(['status' => 'ko', 'message' => 'You must provide user object']);
        }

        
        $user->setNom($userReq['nom'])
        ->setPrenom($userReq['prenom'])
        ->setCivil($userReq['civil'])
        ->setAdresse($userReq['adresse'])
        ->setCp($userReq['cp'])
        ->setVille($userReq['ville'])
        ->setPays($userReq['pays'])
        ->setTel($userReq['tel'])
        ->setMobil($userReq['mobil'])
        ->setEmail($userReq['email'])
        ->setDatnaiss(new \DateTime($userReq['datnaiss']))
        ->setProfession($userReq['profession']);
        if ($userReq['password'] !== '') {
            $password = $encoder->encodePassword($user, $userReq['password']);
            $user->setPassword($password);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return new JsonResponse([
            'username' => $user->getUsername(),
            'roles' => $user->getRoles(),
            'codco' => $user->getCodco(),
            'ident' => $user->getIdent(),
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
            'datnaiss' => $user->getDatnaiss()
        ]);
    }

    /**
    * @Route("/api/password-request", name="admin-password-request", requirements={"methods": "POST"})
    */
    public function passwordRequestAction(Request $request)
    {
        $email = $request->get('email');
        $user = $this->get('doctrine')->getRepository(User::class)->findOneBy(['email' => $email]);
        if (! $user) {
            return new JsonResponse([
                'error' => 'user not found.'
            ], 401);
        }

        $token = sha1(random_bytes(15));
        $expiresAt = new \DateTime();
        $expiresAt->add(new \DateInterval('PT4H'));

        $user->setResetToken($token);
        $user->setResetTokenExpiresAt($expiresAt);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $link = $this->generateUrl('admin-password-reset', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

        $this->mailer->send(
            $email,
            $this->translator->trans('security.reset_password_request.subject'),
            $this->renderView(
                'emails/security-reset-password-request-'.$request->getLocale().'.html.twig',
                ['link' => $link, 'contact' => $user]
            )
        );

        return new JsonResponse(['status' => 'ok', 'message' => 'The email has been sent', 'removeMe' => $link]);
    }
    /**
    * @Route("/admin/password-reset/{token}",
    * name="admin-password-reset", requirements={"methods": "{GET, POST}"})
    */
    public function passwordResetAction(
        Request $request,
        UserPasswordEncoderInterface $encoder,
        $token
    ) {
        $user = $this->get('doctrine')->getRepository(User::class)->findOneBy(['resetToken' => $token]);
        if (!$user) {
            return $this->redirect($this->generateUrl('admin'));
        }

        $form = $this->createFormBuilder($user)
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
            $password = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password)
            ->setResetToken(null)
            ->setResetTokenExpiresAt(null);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('password-reset-success');
        }
        return $this->render('security/password-reset.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
    * @Route("/admin-password-reset-success",
    * name="admin-password-reset-success", requirements={"methods": "{GET, POST}"})
    */
    public function passwordResetSuccessAction(Request $request)
    {
        return $this->render('security/password-reset-success.html.twig', array(
            'user' => null
        ));
    }
}
