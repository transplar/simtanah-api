<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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

    /**
     * @Route("/users/changepassword", name="users_change_password", methods={"POST"})
     */
    public function changePassword(UserPasswordEncoderInterface $passwordEncoder, Request $request, EntityManagerInterface $em)
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json([
                'status' => 'ERROR',
                'message' => 'Only logged in user can change password.',
            ], 401);
        }

        $requsetBody = json_decode($request->getContent(), true);
        $oldPassword = $requsetBody['old_password'];
        $newPassword = $requsetBody['new_password'];
        $checkPass = $passwordEncoder->isPasswordValid($user, $password);

        if (!$checkPass) {
            return $this->json([
                'status' => 'ERROR',
                'message' => 'Invalid old password',
            ], 400);
        }

        $password = $passwordEncoder->encodePassword($user, $newPassword);
        $user->setPassword($password);
        $em->flush();

        return $this->json([
            'status' => 'OK',
            'message' => 'Password changed.',
            'check' => $checkPass,
        ]);
    }
}
