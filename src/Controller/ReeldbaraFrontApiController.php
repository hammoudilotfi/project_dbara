<?php

namespace App\Controller;

use App\Entity\Reeldbara;
use App\Entity\ReeldbaraFront;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
#[Route('/api', name: 'api_')]
class ReeldbaraFrontApiController extends AbstractController
{
    private $slugger;
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }
    #[Route('/recettes/real', name: 'reeldbara_ajout')]
    public function addreeldbara(Request $request ,EntityManagerInterface $entityManager,SluggerInterface $slugger): Response
    {
        $reeldbara = new ReeldbaraFront();
        $reeldbara->setNom($request->request->get('nom'));
        $file = $request->files->get('video');
        if ($file) {
            $fileName = $this->uploadFile($file, $slugger);
            $reeldbara->setVideo($fileName);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($reeldbara);
        $entityManager->flush();
        return $this->json('Created new Reel Dbara successfully with id ' . $reeldbara->getId());
    }
    /**
     * Uploads the file to the server and returns the generated file name.
     */
    private function uploadFile($file, $slugger)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move(
                $this->getParameter('photos_directory'),
                $fileName
            );
        } catch (FileException $e) {
            // Handle the exception if file upload fails
            throw new \Exception('Failed to upload file');
        }

        return $fileName;
    }
    #[Route('/showreeldbara', name: 'showreeldbara',methods: ["GET"])]
    public function showdbaretchef():Response
    {
        $products = $this->getDoctrine()
            ->getRepository(ReeldbaraFront::class)
            ->findAll();
        $data = [];
        foreach ($products as $product) {
            $data[] = [
                'id' => $product->getId(),
                'nom' => $product->getNom(),
                'video'=>$product->getVideo(),
            ];
        }
        return $this->json($data);

    }
    #[Route('/getreeldbara/{id}', name: 'showbyidreeldbara',methods: ["GET"])]
    public function showbyidreeldbara(int $id): Response
    {
        // dd($id);
        $reeldbara = $this->getDoctrine()->getRepository(Reeldbara::class)->find($id);
        if (!$reeldbara) {

            return $this->json('No Reel Dbara found for id' . $id, 404);
        }
        $data[] = [
            'id' => $reeldbara->getId(),
            'video'=>$reeldbara->getVideo(),
        ];
        return $this->json($data);
    }
    #[Route('/updatereeldbara/{id}', name: 'updatereeldbara',methods: ["PUT"])]
    public function updatereeldbara(Request $request,int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $reeldbara = $entityManager->getRepository(ReeldbaraFront::class)->find($id);
        if (!$reeldbara) {
            return $this->json('No Reel Dbara found for id' . $id, 404);
        }
        $reeldbara->setNom($request->request->get('nom'));
        $reeldbara->setVideo($request->request->get('video'));
        $entityManager->flush();
        $data[] = [
            'id' => $reeldbara->getId(),
            'video'=>$reeldbara->getVideo(),
        ];
        return $this->json($data);

    }
    #[Route('/removereeldbara/{id}', name: 'removereeldbara',methods: ["DELETE"])]
    public function removereeldbara(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $reeldbara = $entityManager->getRepository(ReeldbaraFront::class)->find($id);
        if (!$reeldbara) {
            return $this->json('No Reel Dbara found for id' . $id, 404);
        }
        $entityManager->remove($reeldbara);
        $entityManager->flush();

        return $this->json('Deleted a Reel Dbara successfully with id ' . $id);
    }
    /*#[Route('/share/{id}', name: 'share', methods: ['POST'])]
    public function shareVideo(int $id): JsonResponse
    {
        $video = $this->getDoctrine()->getRepository(ReeldbaraFront::class)->find($id);
        if (!$video) {
            throw new NotFoundHttpException('Video not found');
        }
        $facebookShareLink = 'https://www.facebook.com/lotfi.hammoudi.10=' . urlencode($video->getVideo());
        $twitterShareLink = 'https://twitter.com/share?url=' . urlencode($video->getVideo());
        $response = [
            'facebook_share_link' => $facebookShareLink,
            'twitter_share_link' => $twitterShareLink,
        ];
        return new JsonResponse($response, 200);
    }*/
    #[Route('/share/{id}', name: 'share', methods: ['POST'])]
    public function shareVideo(int $id, HttpClientInterface $httpClient, LoggerInterface $logger): JsonResponse
    {
        $video = $this->getDoctrine()->getRepository(ReeldbaraFront::class)->find($id);
        if (!$video) {
            throw new NotFoundHttpException('Video not found');
        }
        $accessToken = 'EAAEioWLgAkIBO0iVHvDy2Q2CBZCCHw3bo8IKKTvo4a2rOh7VCjwv8iOCCOWCPHl1ZBjmhIZCYEfPgMPb1jaq5eAzpQdQ8GBZA0B0YOfJTZCffZBJ22gV5MbMtkGh1Uy1FsqgmkiKg7bj3yHTdr5AsEoZBQ0x5qZCkHZCbxZCGDk0XeVlaUQ1qrZCkQ5IZAE7UvQU80qe8UyhHewr9ut2ZCRRNZCbTPKNmfU79tBzKNmRT7Iwk7lmLdPOcyt9uQnYrr2HT4zAZDZD';
        $videoUrl = $video->getVideo();
        $facebookApiEndpoint = 'https://www.facebook.com/me/lotfihammoudi';
        $postData = [
            'message' => 'Check out this video!',
            'link' => $videoUrl,
        ];
        $response = $httpClient->request('POST', $facebookApiEndpoint, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
            ],
            'json' => $postData,
        ]);
        $content = $response->getContent();
        $statusCode = $response->getStatusCode();
        $logger->info("Facebook API Response: Status Code $statusCode, Content: $content");
        return new JsonResponse(['message' => 'Video shared on Facebook'], 200);
    }
}
