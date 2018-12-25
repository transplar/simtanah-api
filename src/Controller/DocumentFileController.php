<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\DocumentFile;
use App\Repository\DocumentFileRepository;

class DocumentFileController extends AbstractController
{
    /**
     * @Route("/document", name="document_index", methods={"GET"})
     */
    public function index(Request $request, DocumentFileRepository $documentFileRepository)
    {
        $limit = $request->query->get('limit') ?? 100;
        $offset = $request->query->get('page') * $limit ?? 0;
        $document = $documentFileRepository->findBy(
            [],
            ['filename' => 'DESC'],
            $limit,
            $offset
        );

        if (empty($document)) {
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

    private function denied()
    {
        return $this->json([
            'status' => 'ERROR',
            'message' => 'Access denied, please login.',
        ], 401);
    }
}
