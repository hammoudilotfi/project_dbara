<?php

namespace App\Controller;

use App\Entity\Chef;
use App\Repository\ChefRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/api', name: 'api_')]
class ChefController extends AbstractController
{
    #[Route('/addchef', name: 'addchef',methods: ["POST"])]
    public function addsubcategory(Request $request ,EntityManagerInterface $entityManager): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $chef = new Chef();
        $chef->setNom($request->request->get('nom'));
        $chef->setPrenom($request->request->get('prenom'));
        $chef->setNom($request->request->get('nom'));
        $chef->setSpecialite($request->request->get('specialite'));
        $chef->setEmail($request->request->get('email'));
        $chef->setNumeroTelephone($request->request->get('numero_telephone'));
        $chef->setPhoto($request->request->get('photo'));
        $entityManager->persist($chef);
        $entityManager->flush();
        return $this->json('Created new chef successfully with id ' . $chef->getId());
    }
    #[Route('/getchef', name: 'getchef',methods: ["GET"])]
     public function getchef(): Response
    {
        $products = $this->getDoctrine()->getRepository(Chef::class)->findAll();
        $data =[];
        foreach ($products as $product) {
            $data[] = [
                'id' => $product->getId(),
                'nom' => $product->getNom(),
                'prenom' => $product->getPrenom(),
                'specialite'=>$product->getSpecialite(),
                'email' => $product->getEmail(),
                'numero_telephone' => $product->getNumeroTelephone(),
                'photo' => $product->getPhoto(),
            ];
        }
        return $this->json($data);
    }
    #[Route('/showchef/{id}', name: 'showchef',methods: ["GET"])]
    public function showchef(int $id): Response
    {
        $chef =$this->getDoctrine()->getRepository(Chef::class)->find($id);
        if(!$chef) {
            return $this->json('No chef found for id' . $id, 404);
        }
        $data[] = [
            'id' => $chef->getId(),
            'nom' => $chef->getNom(),
            'prenom' => $chef->getPrenom(),
            'specialite'=>$chef->getSpecialite(),
            'email' => $chef->getEmail(),
            'numero_telephone' => $chef->getNumeroTelephone(),
            'photo' => $chef->getPhoto(),
        ];
        return $this->json($data);
    }
    #[Route('/searchchef/{nom}', name: 'searchchef', methods: ['GET'])]
    public function searchByName(Request $request, ChefRepository $chefRepository,string $nom): Response
    {
        $chefs = $chefRepository->findChefByName($nom);
        if (empty($chefs)) {
            return $this->json('No chef found with name: ' . $nom, 404);
        }
        $data = [];
        foreach ($chefs as $chef) {
            $data[] = [
                'id' => $chef->getId(),
                'nom' => $chef->getNom(),
                'prenom' => $chef->getPrenom(),
                'specialite'=>$chef->getSpecialite(),
                'email' => $chef->getEmail(),
                'numero_telephone' => $chef->getNumeroTelephone(),
                'photo' => $chef->getPhoto(),
            ];
        }
        return $this->json($data);
    }
}
