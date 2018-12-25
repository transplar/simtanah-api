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

    /**
     * @Route("/document", name="document_new", methods={"POST"})
     */
    public function new(Request $request, EntityManagerInterface $em)
    {
        if (!$this->getUser()) {
            return $this->denied();
        }

        $body = json_decode($request->getContent(), true);
        if (!isset($body['url']) || !isset($body['filename']) || !isset($body['document_type'])) {
            return $this->json([
                'status' => 'ERROR',
                'message' => 'Invalid requset',
            ], 400);
        }

        $document = new DocumentFile;
        $document->setUrl($body['url'])
            ->setFilename($body['filename'])
            ->setDocumentType($body['document_type'])
        ;
        $em->persist($document);
        $em->flush();

        return $this->json([
            'status' => 'OK',
            'message' => 'Saved successfully.',
            'item' => $document,
        ]);
    }

    /**
     * @Route("/document", name="document_update", methods={"PATCH"})
     */
    public function update(Request $request, EntityManagerInterface $em, DocumentFileRepository $documentFileRepository)
    {
        $response = new JsonResponse;
        $content = [];

        if (!$this->getUser()) {
            return $this->denied();
        }

        $body = json_decode($request->getContent(), true);
        try {
            $document = $documentFileRepository->findOneBy(['id' => $body['id']]);
            $document->setUrl($body['url'])
                ->setFilename($body['filename'])
                ->setDocumentType($body['document_type'])
            ;
            $em->flush();
            $content['status'] = 'OK';
            $content['message'] = 'Succesfully updated.';
            $content['item'] = $document;
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
