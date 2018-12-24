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
    public function upload(Request $request)
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

        return $this->json($this->save($request, $_FILES));
    }

    private function save(Request $request, $file)
    {
        $uploadDir          = $this->getParameter('kernel.project_dir');
        $originalFilename   = $_FILES['file']['name'];
        $temporaryFilename  = $_FILES['file']['tmp_name'];
        $hashValue          = hash_file('crc32', $temporaryFilename);
        $fileExtension      = (new \SplFileInfo($originalFilename))->getExtension();
        $filename           = $hashValue . '.' . $fileExtension;
        $file               = $uploadDir.'/public/upload/'.$filename;
        $url                = $request->getUri(). '/' . $filename;
        move_uploaded_file($temporaryFilename, $file);

        return [
            'status'        => 'OK',
            'filename'      => $filename,
            'url'           => $url,
            'file'          => $_FILES,
        ];
    }
}
