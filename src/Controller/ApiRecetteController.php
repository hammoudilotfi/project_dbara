<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Repository\SubcategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class ApiRecetteController extends AbstractController
{
    private $SubcategoryRepository;

    public function __construct(SubcategoryRepository $SubcategoryRepository)
    {
        $this->SubcategoryRepository = $SubcategoryRepository;
    }
    #[Route('/getrecette', name: 'getrecette',methods: ["GET"])]
    public function getRecettes():Response
    {
        $products = $this->getDoctrine()
            ->getRepository(Recette::class)
            ->findAll();
        $data = [];
        foreach ($products as $product) {
            $data[] = [
                'id' => $product->getId(),
                'updated_at' =>$product->getUpdatedAt(),
                'nom' => $product->getNom(),
                'description' => $product->getDescription(),
                'date_creation' =>$product->getDateCreation(),
                'temps_prepartion'=>$product->getTempsPreparation(),
                'niv_difficulte'=>$product->getNivDifficulte(),
                'temperature'=>$product->getTemperature(),
                'cost'=>$product->getCost(),
                'photo'=>$product->getPhoto(),
                'video'=>$product->getVideo(),
            ];
        }


        return $this->json($data);



    }

    #[Route('/addrecette', name: 'addrecette',methods: ["POST"])]
    public function addRecette(Request $request ,EntityManagerInterface $entityManager): Response
    {

        $recette = new Recette();
        $recette->setUpdatedAt(new \DateTime());
        $recette->setNom($request->request->get('nom'));
        $recette->setDescription($request->request->get('description'));
        $recette->setDateCreation(new \DateTime());
        $recette->setTempsPreparation($request->request->get('temps_preparation'));
        $recette->setNivDifficulte($request->request->get('niv_difficulte'));
        $recette->setTemperature($request->request->get('temperature'));
        $recette->setCost($request->request->get('cost'));
        $recette->setPhoto($request->request->get('photo'));
        $recette->setVideo($request->request->get('video'));
        //init subcategory-id
        $subcatecory_id=(int)$request->request->get('subcategory_id');

        if ($subcatecory_id){
            $subcatecory=$this->SubcategoryRepository->find($subcatecory_id);
            //dd($subcatecory);exit;
            if(!empty($subcatecory)){
                $recette->setSubcategory($subcatecory);
            }
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($recette);
        $entityManager->flush();
        return $this->json('Created new recette successfully with id ' . $recette->getId());
    }
    #[Route('/showrecette/{id}', name: 'showrecette',methods: ["GET"])]
    public function showRecettes(int $id): Response
    {
       // dd($id);
        $recette = $this->getDoctrine()->getRepository(Recette::class)->find($id);
        if (!$recette) {

            return $this->json('No recette found for id' . $id, 404);
        }
        $data[] = [
            'id' => $recette->getId(),
            'updated_at' =>$recette->getUpdatedAt(),
            'nom' => $recette->getNom(),
            'description' => $recette->getDescription(),
            'date_creation' =>$recette->getDateCreation(),
            'temps_prepartion'=>$recette->getTempsPreparation(),
            'niv_difficulte'=>$recette->getNivDifficulte(),
            'temperature'=>$recette->getTemperature(),
            'cost'=>$recette->getCost(),
            'photo'=>$recette->getPhoto(),
            'video'=>$recette->getVideo(),
        ];
        return $this->json($data);
    }
  /*  #[Route('/getrecettebysubcategory/{subcategory_id}', name: 'getrecettebysubcategory',methods: ["GET"])]
    public function getrecettebysubcategory(int $subcategory_id): Response
    {
        // dd($id);
        $recette = $this->getDoctrine()->getRepository(Recette::class)->find($subcategory_id);
        if (!$recette) {

            return $this->json('No recette found for subcategory_id' . $subcategory_id, 404);
        }
        $data[] = [
            'id' => $recette->getId(),
            'updated_at' =>$recette->getUpdatedAt(),
            'nom' => $recette->getNom(),
            'description' => $recette->getDescription(),
            'date_creation' =>$recette->getDateCreation(),
            'temps_prepartion'=>$recette->getTempsPreparation(),
            'niv_difficulte'=>$recette->getNivDifficulte(),
            'temperature'=>$recette->getTemperature(),
            'cost'=>$recette->getCost(),
            'photo'=>$recette->getPhoto(),
            'video'=>$recette->getVideo(),
            'subcategory_id'=>$recette->getSubcategory(),
        ];
        return $this->json($data);
    }*/
    #[Route('/editrecette/{id}', name: 'editrecette',methods: ["PUT"])]
    public function edit(Request $request,int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $recette = $entityManager->getRepository(Recette::class)->find($id);
        if (!$recette) {
            return $this->json('No recette found for id' . $id, 404);
        }
        $recette->setUpdatedAt(new \DateTime());
        $recette->setNom($request->request->get('nom'));
        $recette->setDescription($request->request->get('description'));
        $recette->setDateCreation(new \DateTime());
        $recette->setTempsPreparation($request->request->get('temps_preparation'));
        $recette->setNivDifficulte($request->request->get('niv_difficulte'));
        $recette->setTemperature($request->request->get('temperature'));
        $recette->setCost($request->request->get('cost'));
        $recette->setPhoto($request->request->get('photo'));
        $recette->setVideo($request->request->get('video'));
        $entityManager->flush();
        $data[] = [
            'id' => $recette->getId(),
            'updated_at' =>$recette->getUpdatedAt(),
            'nom' => $recette->getNom(),
            'description' => $recette->getDescription(),
            'date_creation' =>$recette->getDateCreation(),
            'temps_prepartion'=>$recette->getTempsPreparation(),
            'niv_difficulte'=>$recette->getNivDifficulte(),
            'temperature'=>$recette->getTemperature(),
            'cost'=>$recette->getCost(),
            'photo'=>$recette->getPhoto(),
            'video'=>$recette->getVideo(),
        ];
        return $this->json($data);

    }

    #[Route('/deleterecette/{id}', name: 'deleterecette',methods: ["DELETE"])]
    public function delete(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $recette = $entityManager->getRepository(Recette::class)->find($id);
        if (!$recette) {
            return $this->json('No recette found for id' . $id, 404);
        }
        $entityManager->remove($recette);
        $entityManager->flush();

        return $this->json('Deleted a recette successfully with id ' . $id);
    }
}
