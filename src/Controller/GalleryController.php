<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Gallery;
use App\Repository\GalleryRepository;

class GalleryController extends AbstractController
{
    /**
     * @Route("/gallery", name="gallery_index", methods={"GET"})
     */
    public function index(Request $request, GalleryRepository $galleryRepository)
    {
        $limit = $request->query->get('limit') ?? 100;
        $offset = $request->query->get('page') * $limit ?? 0;
        $gallery = $galleryRepository->findBy(
            [],
            ['event_date' => 'DESC'],
            $limit,
            $offset
        );

        if (empty($gallery)) {
            return $this->json([
                'status' => 'ERROR',
                'message' => 'Items not found',
            ], 404);
        }

        return $this->json([
            'status' => 'OK',
            'items' => $gallery,
        ]);
    }

    /**
     * @Route("/gallery", name="gallery_new", methods={"POST"})
     */
    public function new(Request $request, EntityManagerInterface $em)
    {
        if (!$this->getUser()) {
            return $this->denied();
        }

        $body = json_decode($request->getContent(), true);
        if (!isset($body['url']) || !isset($body['caption'])) {
            return $this->json([
                'status' => 'ERROR',
                'message' => 'Invalid requset',
            ], 400);
        }

        $gallery = new Gallery;
        try {
            $eventDate = new \DateTime($body['event_date']);
        } catch (\Exception $e) {
            $eventDate = new \DateTime();
        }
        $gallery->setUrl($body['url'])
            ->setCaption($body['caption'])
            ->setEventDate($eventDate)
        ;
        $em->persist($gallery);
        $em->flush();

        return $this->json([
            'status' => 'OK',
            'message' => 'Saved successfully.',
            'item' => $gallery,
        ]);
    }

    /**
     * @Route("/gallery", name="gallery_update", methods={"PATCH"})
     */
    public function update(Request $request, EntityManagerInterface $em, GalleryRepository $galleryRepository)
    {
        $response = new JsonResponse;
        $content = [];

        if (!$this->getUser()) {
            return $this->denied();
        }

        $body = json_decode($request->getContent(), true);
        try {
            $gallery = $galleryRepository->findOneBy(['id' => $body['id']]);
            $eventDate = new \DateTime($body['event_date']) ?? new \DateTime;
            $gallery->setUrl($body['url'])
                ->setCaption($body['caption'])
                ->setEventDate($eventDate)
            ;
            $em->flush();
            $content['status'] = 'OK';
            $content['message'] = 'Succecfully updated.';
            $content['item'] = $gallery;
        } catch (\Exception $e) {
            $content['status'] = 'ERROR';
            $content['message'] = 'Failed to update.';
            $response->setStatusCode(400);
        }
        $response->setData($content);
        return $response;
    }

    private function denied()
    {
        return $this->json([
            'status' => 'ERROR',
            'message' => 'Access denied, please login.',
        ], 401);
    }
}
