<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Config;
use App\Repository\ConfigRepository;

class ConfigController extends AbstractController
{
    /**
     * @Route("/config/{name}", name="config_fetch", methods={"GET"})
     */
    public function fetch(ConfigRepository $configRepository, $name)
    {
        $config = $configRepository->findOneBy(['name' => $name]);
        if (!$config) {
            return $this->json([
                'status' => 'ERROR',
                'message' => 'Config name not found',
            ], 404);
        }
        return $this->json([
            'status' => 'OK',
            'config' => $config,
        ]);
    }

    /**
     * @Route("/config", name="config_update", methods={"PATCH"})
     */
    public function update(Request $request, EntityManagerInterface $em, ConfigRepository $configRepository)
    {
        if (!$this->getUser()) {
            return $this->json([
                'status' => 'ERROR',
                'message' => 'Anda tidak berwenang mengakses halaman ini.'
            ], 401);
        }

        $input = json_decode($request->getContent(), true);
        $name = $input['name'];
        $config = $configRepository->findOneBy(['name' => $name]);
        $config->setContent($input['content']);
        $em->flush();
        return $this->json([
            'status' => 'OK',
            'message' => 'Config saved successfully',
            'item' => $config,
        ]);
    }
}
