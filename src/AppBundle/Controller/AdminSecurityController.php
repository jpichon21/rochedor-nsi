<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Translation\TranslatorInterface as Translator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Repository\UserRepository;
use AppBundle\Service\Mailer;
use AppBundle\Entity\User;

class AdminSecurityController extends Controller
{
     /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @var Translator
     */
    private $translator;

    public function __construct(
        Mailer $mailer,
        Translator $translator
    ) {
        $this->mailer = $mailer;
        $this->translator = $translator;
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

        return new JsonResponse($user);
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
        $lastname = $request->get('lastname');
        $firstname = $request->get('firstname');
        if (!$email || !$lastname || !$firstname) {
            return new JsonResponse(['status' => 'ko', 'message' => 'security.password_request.missing_infos']);
        }
        
        
        $token = sha1(random_bytes(15));
        $expiresAt = new \DateTime();
        $expiresAt->add(new \DateInterval('PT4H'));
        foreach ($users as $user) {
            $user->setResetToken($token)
            ->setResetTokenExpiresAt($expiresAt);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $link = $this->generateUrl('password-reset', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);
            $this->mailer->send(
                $email,
                $this->translator->trans('security.reset_password_request.subject'),
                $this->renderView(
                    'emails/security-reset-password-request-'.$request->getLocale().'.html.twig',
                    ['link' => $link, 'user' => $user]
                )
            );
        }
        return new JsonResponse(['status' => 'ok', 'message' => 'The email has been sent']);
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
        $user = $repository->findUserByToken($token);
        if (!$user) {
            return $this->render('security/password-reset.html.twig', ['user' => null]);
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
