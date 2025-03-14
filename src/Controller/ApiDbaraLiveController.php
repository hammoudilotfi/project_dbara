<?php

namespace App\Controller;

use App\Entity\Dbaralive;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/api', name: 'api_')]
class ApiDbaraLiveController extends AbstractController
{
    private $slugger;
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }
    #[Route('/adddbaralive', name: 'ajoutbaralive')]
    public function adddbaralive(Request $request ,EntityManagerInterface $entityManager,SluggerInterface $slugger): Response
    {
        $uploadedPhoto = $request->files->get('photo');
        $photoDirectory = $this->getParameter('kernel.project_dir') . '/public/uploads/photos';
        $photoFileName = md5(uniqid()) . '.' . $uploadedPhoto->getClientOriginalExtension();
        $uploadedPhoto->move($photoDirectory, $photoFileName);
        var_dump($uploadedPhoto);
        $uploadedVideo = $request->files->get('video');
        $videoDirectory = $this->getParameter('kernel.project_dir') . '/public/uploads/videos';
        $videoFileName = md5(uniqid()) . '.' . $uploadedVideo->getClientOriginalExtension();
        var_dump($uploadedVideo);
        $uploadedVideo->move($videoDirectory, $videoFileName);
        // Create the Dbaretelchef entity and set its properties
        $dbaralive = new Dbaralive();
        $dbaralive->setType($request->request->get('type'));
        $dbaralive->setNom($request->request->get('nom'));
        $dbaralive->setDescription($request->request->get('description'));
        $dbaralive->setTempsPreparation($request->request->get('temps_preparation'));
        $dbaralive->setNombreIngredient($request->request->get('nombre_ingredient'));
        $dbaralive->setNivDifficulte($request->request->get('niv_difficulte'));
        $dbaralive->setIngredient($request->request->get('ingredient'));
        $dbaralive->setApportsNutritifs($request->request->get('apports_nutritifs'));
        $dbaralive->setPhoto($photoFileName);
        $dbaralive->setVideo($videoFileName);

        $entityManager->persist($dbaralive);
        $entityManager->flush();
        return $this->json('Created new Dbara Live successfully with id ' . $dbaralive->getId());
    }

    #[Route('/getdbaralive', name: 'getdbaralive',methods: ["GET"])]
    public function getdbaralive():Response
    {
        $products = $this->getDoctrine()
            ->getRepository(Dbaralive::class)
            ->findAll();
        $data = [];
        foreach ($products as $product) {
            $data[] = [
                'id' => $product->getId(),
                'type' => $product->getType(),
                'nom' => $product->getNom(),
                'description' => $product->getDescription(),
                'temps_prepartion'=>$product->getTempsPreparation(),
                'nombre_ingredient'=>$product->getNombreIngredient(),
                'niv_difficulte'=>$product->getNivDifficulte(),
                'ingredients'=>$product->getIngredients(),
                'apports_nutritifs'=>$product->getApportsNutritifs(),
                'photo'=>$product->getPhoto(),
                'video'=>$product->getVideo(),
            ];
        }
        return $this->json($data);

    }
    #[Route('/showdbaralive/{id}', name: 'showdbaralive',methods: ["GET"])]
    public function showdbaralive(int $id): Response
    {
        // dd($id);
        $dbaralive = $this->getDoctrine()->getRepository(Dbaralive::class)->find($id);
        if (!$dbaralive) {

            return $this->json('No Dbara Live found for id' . $id, 404);
        }
        $data[] = [
            'id' => $dbaralive->getId(),
            'type' => $dbaralive->getType(),
            'nom' => $dbaralive->getNom(),
            'description' => $dbaralive->getDescription(),
            'temps_prepartion'=>$dbaralive->getTempsPreparation(),
            'nombre_ingredient'=>$dbaralive->getNombreIngredient(),
            'niv_difficulte'=>$dbaralive->getNivDifficulte(),
            'ingredients'=>$dbaralive->getIngredients(),
            'apports_nutritifs'=>$dbaralive->getApportsNutritifs(),
            'photo'=>$dbaralive->getPhoto(),
            'video'=>$dbaralive->getVideo(),
        ];
        return $this->json($data);
    }
    #[Route('/editdbaralive/{id}', name: 'editdbaralive',methods: ["PUT"])]
    public function editdbaralive(Request $request,int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $dbaralive = $entityManager->getRepository(Dbaralive::class)->find($id);
        if (!$dbaralive) {
            return $this->json('No Dbaret chef found for id' . $id, 404);
        }
        $dbaralive->setType($request->request->get('type'));
        $dbaralive->setNom($request->request->get('nom'));
        $dbaralive->setDescription($request->request->get('description'));
        $dbaralive->setTempsPreparation($request->request->get('temps_preparation'));
        $dbaralive->setNombreIngredient($request->request->get('nombre_ingredient'));
        $dbaralive->setNivDifficulte($request->request->get('niv_difficulte'));
        $dbaralive->setIngredient($request->request->get('ingredient'));
        $dbaralive->setApportsNutritifs($request->request->get('apports_nutritifs'));
        $dbaralive->setPhoto($request->request->get('photo'));
        $dbaralive->setVideo($request->request->get('video'));
        $entityManager->flush();
        $data[] = [
            'id' => $dbaralive->getId(),
            'type' => $dbaralive->getType(),
            'nom' => $dbaralive->getNom(),
            'description' => $dbaralive->getDescription(),
            'temps_prepartion'=>$dbaralive->getTempsPreparation(),
            'nombre_ingredient'=>$dbaralive->getNombreIngredient(),
            'niv_difficulte'=>$dbaralive->getNivDifficulte(),
            'ingredients'=>$dbaralive->getIngredients(),
            'apports_nutritifs'=>$dbaralive->getApportsNutritifs(),
            'photo'=>$dbaralive->getPhoto(),
            'video'=>$dbaralive->getVideo(),
        ];
        return $this->json($data);

    }
    #[Route('/deletedbaralive/{id}', name: 'deletedbaralive',methods: ["DELETE"])]
    public function deletedbaralive(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $dbaralive = $entityManager->getRepository(Dbaralive::class)->find($id);
        if (!$dbaralive) {
            return $this->json('No Dbara Live found for id' . $id, 404);
        }
        $entityManager->remove($dbaralive);
        $entityManager->flush();

        return $this->json('Deleted a Dbara Live successfully with id ' . $id);
    }
}
