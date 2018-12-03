<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use App\Entity\News;

class NewsController extends AbstractController
{
    /**
     * @Route("/news", name="create_news", methods={"POST"})
     */
    public function index(Request $request, EntityManagerInterface $em)
    {
        $response = new JsonResponse;
        $user = $this->getUser();

        if (!$user) {
            return $response->setStatusCode(401);
        }

        $news = new News;
        $serializer = new Serializer([new ObjectNormalizer], [new JsonEncoder]);
        $content = [];

        $data = json_decode($request->getContent(), true);
        $now = new \DateTime();
        $news->setTitle($data['title'])
            ->setContent($data['content'])
            ->setWriter($user)
            ->setPublishedOn($now)
            ->setLastUpdate($now)
        ;

        $em->persist($news);
        $em->flush();

        $content['status'] = 'OK';
        $content['message'] = 'Berita berhasil tersimpan.';
        $content['item'] = json_decode($serializer->serialize($news, 'json'), true);

        return $response->setData($content);
    }
}
