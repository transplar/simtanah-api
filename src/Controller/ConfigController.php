<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
