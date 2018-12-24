<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FileUploadController extends AbstractController
{
    /**
     * @Route("/upload", name="file_upload", methods={"POST"})
     */
    public function index(Request $request)
    {
        if (!$this->getUser()) {
            return $this->json([
                'status' => 'ERROR',
                'message' => 'Access Denied, please login.',
            ], 403);
        }

        if (!isset($_FILES['file']) || empty($_FILES['file'])) {
            return $this->json([
                'status' => 'ERROR',
                'message' => 'No resource to be uploaded, file cannot be empty.',
            ], 400);
        }

        $uploadDir = $this->getParameter('kernel.project_dir');
        $hashValue = hash_file('crc32', $_FILES['file']['tmp_name']);
        $filename = $hashValue . '-' . $_FILES['file']['name'];
        $file = $uploadDir.'/public/upload/'.$filename;
        move_uploaded_file($_FILES['file']['tmp_name'], $file);
        $url = $request->getUri(). '/' . $filename;

        return $this->json([
            'status' => 'OK',
            'url' => $url,
            'filename' => $filename,
        ]);
    }
}
