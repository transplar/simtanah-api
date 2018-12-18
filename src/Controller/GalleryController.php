<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
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
}
