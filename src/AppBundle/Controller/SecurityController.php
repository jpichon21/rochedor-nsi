<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SecurityController extends Controller
{
    /**
    * @Route("/login", name="login", requirements={"methods": "POST"})
    */
    public function loginAction(Request $request)
    {
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse([
                'status' => 'not logged in'
            ], 201);
        }
        
        return new JsonResponse([
            'username' => $user->getUsername(),
            'roles' => $user->getRoles()
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
