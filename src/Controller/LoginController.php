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
                'roles' => $user->getRoles(),
            ];
        }
        $response->setData($content)
            ->setEncodingOptions(1);

        return $response;
    }
}
