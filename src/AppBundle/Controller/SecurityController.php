<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swagger\Annotations as SWG;
use AppBundle\Entity\Contact;
use AppBundle\Repository\ContactRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends Controller
{
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
    * @Route("/logout", name="logout")
    */
    public function logoutAction(Request $request)
    {
        return new JsonResponse(['status' => 'ok']);
    }
}
