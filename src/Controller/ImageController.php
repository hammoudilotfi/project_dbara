<?php

namespace App\Controller;

use App\Entity\Image;
use App\Repository\ImageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/api', name: 'api_')]
class ImageController extends AbstractController
{
    #[Route('/upload-image', name: 'upload_image', methods: ['POST'])]
    public function uploadImage(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['filename']) || empty($data['filename'])) {
            return new JsonResponse(['message' => 'Invalid or empty "filename" in JSON data'], 400);
        }
        $filename = $data['filename'];
        $image = new Image();
        $image->setFilename($filename);
        $image->setClientId($this->getUser()->getId());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($image);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Image uploaded successfully']);
    }
    #[Route('/get-images', name: 'api_get_images', methods: ['GET'])]
    public function getImages(ImageRepository $imageRepository): Response
    {
        $images = $imageRepository->findAll();
        $imageData = [];
        foreach ($images as $image) {
            $imageUrl = '/uploads/images/' . $image->getFilename();
            $clientId = $image->getClientId(); 
            $imageData[] = [
                'imageUrl' => $imageUrl,
                'clientId' => $clientId,
            ];
        }
        return $this->json(['images' => $imageData]);
    }
}
