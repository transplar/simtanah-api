<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    /**
     * @Route("/users/login", name="login", methods={"POST"})
     */
    public function login()
    {
        $user = $this->getUser();
        return $this->json([
            'username' => $user->getUsername(),
            'roles' => $user->getRoles(),
        ]);
    }

    /**
     * @Route("/users/logout", name="users_logout", methods={"GET"})
     */
    public function logout()
    {
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

    /**
     * @Route("/users/me", name="me", methods={"GET"})
     */
    public function me()
    {
        $response = new JsonResponse;
        $content = [];
        $user = $this->getUser();

        if (!$user) {
            $content['status'] = 'ERROR';
            $content['message'] = 'Anda belum login.';
            $response->setStatusCode(401);
        } else {
            $content['status'] = 'OK';
            $content['user'] = [
                'username' => $user->getUsername(),
                'fullname' => $user->getFullname(),
                'roles' => $user->getRoles(),
            ];
        }
        $response->setData($content);

        return $response;
    }
}
