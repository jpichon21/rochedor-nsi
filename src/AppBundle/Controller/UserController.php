<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations\Get;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Gedmo\Loggable;
use Swagger\Annotations as SWG;
use AppBundle\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends Controller
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(
        UserRepository $userRepository,
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        UserPasswordEncoderInterface $encoder
    ) {
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->validator = $validator;
        $this->encoder = $encoder;
    }

    /**
    * @Rest\Post("/user")
    * @Rest\View()
    * @ParamConverter("user", converter="fos_rest.request_body")
    * @SWG\Post(
    *   path="/user",
    *   summary="Add a new user",
    *   @SWG\Parameter(
    *          name="body",
    *          in="body",
    *          required=true,
    *          @SWG\Schema(
    *              @SWG\Property(
    *                  property="name",
    *                  type="string"
    *              ),
    *              @SWG\Property(
    *                  property="title",
    *                  type="object"
    *              ),
    *              @SWG\Property(
    *                  property="description",
    *                  type="object"
    *              ),
    *              @SWG\Property(
    *                  property="image",
    *                  type="string"
    *              ),
    *              @SWG\Property(
    *                  property="position",
    *                  type="integer"
    *              ),
    *          )
    *     ),
    *   @SWG\Response(
    *     response=200,
    *     description="The created user"
    *   )
    * )
    */
    public function postAction(User $user)
    {
        if (!$this->userRepository->isUsernameUnique($user->getUsername())) {
            return new JsonResponse(['status' => 'ko', 'message' => 'security.username_exists']);
        }
        $user->__construct();
        $user->setPassword($this->encoder->encodePassword($user, $user->getPassword()));
        
        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            return new JsonResponse(['status' => 'ko', 'message' => (string)$errors]);
        }
        $user->setRoles(['ROLE_ADMIN']);
        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }

    /**
     * @Rest\Get("/user")
     * @Rest\View()
     * @SWG\Get(
     *  path="/user",
     *      summary="Get requested users'list ordered by position",
     *      @SWG\Response(
     *        response=200,
     *        description="The requested users"
     *      )
     *    )
     */
    public function listAction(Request $request)
    {
        return $this->userRepository->findAll();
    }

    /**
     * @Rest\Get("/user/{id}")
     * @Rest\View()
     * @SWG\Get(
     *  path="/user/{id}",
     *      summary="Get a user",
     *      @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          description="The user id",
     *          required=true,
     *          type="integer"
     *      ),
     *      @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          description="The user id",
     *          required=true,
     *          type="integer"
     *      ),
     *      @SWG\Response(
     *        response=200,
     *        description="The requested user"
     *      ),
     *      @SWG\Response(
     *        response=404,
     *        description="User not found"
     *      ),
     *    )
     */
    public function showAction($id)
    {
        $user = $this->userRepository->find($id);
        if (empty($user)) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        return $user;
    }

    /**
     * @Rest\Delete("/user/{id}")
     * @Rest\View()
     * @SWG\Delete(
     *  path="/user/id",
     *      summary="Delete requested user",
     *      @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          description="user id",
     *          required=true,
     *          type="string"
     *      ),
     *      @SWG\Response(
     *        response=200,
     *        description="The user is deleted"
     *      ),
     *      @SWG\Response(
     *        response=404,
     *        description="User not found"
     *      )
     *    )
     */
    public function deleteAction($id)
    {
        $data = new User;
        $em = $this->getDoctrine()->getManager();
        $user = $this->userRepository->find($id);
        if (empty($user)) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        } else {
            $em->remove($user);
            $em->flush();
        }
        return new JsonResponse(['message' => 'user deleted'], Response::HTTP_OK);
    }

    /**
     * @Rest\Put("/user/{id}")
     * @Rest\View()
     * @SWG\Put(
     *   path="/user/{id}",
     *   summary="Edit requester user",
     *   @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          required=true,
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="name",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="title",
     *                  type="object"
     *              ),
     *              @SWG\Property(
     *                  property="description",
     *                  type="object"
     *              ),
     *              @SWG\Property(
     *                  property="image",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="position",
     *                  type="integer"
     *              ),
     *          )
     *     ),
     *      @SWG\Parameter(
     *          name="id",
     *          in="path",
     *          description="user id",
     *          required=true,
     *          type="string"
     *      ),
     *   @SWG\Response(
     *     response=200,
     *     description=""
     *   ),
     *   @SWG\Response(
     *     response=404,
     *     description="User not found"
     *   )
     * )
     */
    public function putAction($id, Request $request)
    {
        $user = $this->userRepository->find($id);
        if (empty($user)) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        $name = $request->get('name');
        $username = $request->get('username');
        $email = $request->get('email');
        $password = $request->get('password');
        $active = $request->get('active');
        $roles = $request->get('roles');

        if (!$this->userRepository->isUsernameUnique($username, $id)) {
            return new JsonResponse(['status' => 'ko', 'message' => 'security.username_exists']);
        }
        
        $user->setName($name);
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setActive($active);
        $user->setRoles($roles);
        
        if (!empty($password)) {
            $user->setPassword($this->encoder->encodePassword($user, $password));
        }
        
        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            return new JsonResponse(['status' => 'ko', 'message' => (string)$errors]);
        }
        $this->em->persist($user);
        $this->em->flush();
        return new JsonResponse(['message' => 'User Updated'], Response::HTTP_OK);
    }
}
