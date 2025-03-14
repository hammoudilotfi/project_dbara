<?php

namespace App\Controller;

use App\Entity\DbaraLiveFront;
use App\Entity\Reeldbara;
use App\Entity\ReeldbaraFront;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/api', name: 'api_')]
class DbaraLiveFrontApiController extends AbstractController
{
    private $slugger;
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }
    #[Route('/recettes/live', name: 'dbara_ajout')]
    public function ajoutdbaralive(Request $request ,EntityManagerInterface $entityManager,SluggerInterface $slugger): Response
    {
        $dbaralive = new DbaraLiveFront();
        $dbaralive->setNom($request->request->get('nom'));
        $file = $request->files->get('video');
        if ($file) {
            $fileName = $this->uploadFile($file, $slugger);
            $dbaralive->setVideo($fileName);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($dbaralive);
        $entityManager->flush();
        return $this->json('Created new DbaraLive successfully with id ' . $dbaralive->getId());
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
    #[Route('/showdbaralive', name: 'showdbaralive',methods: ["GET"])]
    public function getdbaralive():Response
    {
        $products = $this->getDoctrine()
            ->getRepository(DbaraLiveFront::class)
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
    #[Route('/getdbaralive/{id}', name: 'showbyiddbaralive',methods: ["GET"])]
    public function showbyiddbaralive(int $id): Response
    {
        // dd($id);
        $dbaralive = $this->getDoctrine()->getRepository(DbaraLiveFront::class)->find($id);
        if (!$dbaralive) {

            return $this->json('No DbaraLive found for id' . $id, 404);
        }
        $data[] = [
            'id' => $dbaralive->getId(),
            'video'=>$dbaralive->getVideo(),
        ];
        return $this->json($data);
    }
    #[Route('/updatedbaralive/{id}', name: 'updatedbaralive',methods: ["PUT"])]
    public function updatedbaralive(Request $request,int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $dbaralive = $entityManager->getRepository(DbaraLiveFront::class)->find($id);
        if (!$dbaralive) {
            return $this->json('No DbaraLive found for id' . $id, 404);
        }
        $dbaralive->setNom($request->request->get('nom'));
        $dbaralive->setVideo($request->request->get('video'));
        $entityManager->flush();
        $data[] = [
            'id' => $dbaralive->getId(),
            'video'=>$dbaralive->getVideo(),
        ];
        return $this->json($data);

    }
    #[Route('/removedbaralive/{id}', name: 'removedbaralive',methods: ["DELETE"])]
    public function removedbaralive(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $dbaralive = $entityManager->getRepository(DbaraLiveFront::class)->find($id);
        if (!$dbaralive) {
            return $this->json('No DbaraLive found for id' . $id, 404);
        }
        $entityManager->remove($dbaralive);
        $entityManager->flush();

        return $this->json('Deleted a DbaraLive successfully with id ' . $id);
    }
}
