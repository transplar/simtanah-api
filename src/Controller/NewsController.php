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
use App\Repository\NewsRepository;

class NewsController extends AbstractController
{
    /**
     * @Route("/news", name="news_list", methods={"GET"})
     */
    public function list(Request $request, NewsRepository $newsRepository)
    {
        $response = new JsonResponse;
        $content = [];
        $limit = $request->query->get('limit') ?? 20;
        $offset = $request->query->get('page') ?? 0;
        $news = $newsRepository->findBy(
            [],
            ['last_update' => 'DESC'],
            $limit,
            $offset
        );
        $news = $this->serialize($news, true);
        if (empty($news)) {
            $content['status'] = 'ERROR';
            $content['message'] = 'Resoure empty.';
            $response->setStatusCode(404);
        } else {
            $content['status'] = 'OK';
        }
        $content['items'] = $news;
        return $response->setData($content);
    }

    /**
     * @Route("/news", name="news_create", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $em)
    {
        $response = new JsonResponse;
        $user = $this->getUser();

        if (!$user) {
            return $response->setStatusCode(401);
        }

        $news = new News;
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
        $content['item'] = $this->serialize($news, true);

        return $response->setData($content);
    }

    private function serialize($data, bool $decode = false)
    {
        $serializer = new Serializer([new ObjectNormalizer], [new JsonEncoder]);

        if (!$decode) {
            return $serializer->serialize($data, 'json');
        }
        return json_decode($serializer->serialize($data, 'json'), true);
    }
}
