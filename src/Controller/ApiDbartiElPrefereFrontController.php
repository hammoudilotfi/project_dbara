<?php

namespace App\Controller;

use App\Entity\Dbaretelchef;
use App\Entity\DbartiElPrefere;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class ApiDbartiElPrefereFrontController extends AbstractController
{

    #[Route('/dbaretchef/{id}/add-to-preferred', name: 'add_to_preferred',methods: ["POST"])]
    public function addToPreferred(Request $request, $id): Response
    {
        $dbaretchef = $this->getDoctrine()->getRepository(Dbaretelchef::class)->find($id);
        if (!$dbaretchef) {

            return $this->json('No Dbaretchef found for id' . $id, 404);
        }
        $preferredRecipe = new DbartiElPrefere();
        $preferredRecipe->setRecipe($dbaretchef);
        $preferredRecipe->setType($dbaretchef->getType('type'));
        $preferredRecipe->setNom($dbaretchef->getNom('nom'));
        $preferredRecipe->setDescription($dbaretchef->getDescription('description'));;
        $preferredRecipe->setTempsPreparation($dbaretchef->getTempsPreparation('temps_preparation'));
        $preferredRecipe->setNombreIngredient($dbaretchef->getNombreIngredient('nombre_ingredient'));
        $preferredRecipe->setNivDifficulte($dbaretchef->getNivDifficulte('niv_difficulte'));
        $preferredRecipe->setIngredients($dbaretchef->getIngredients('ingredients'));
        $preferredRecipe->setApportsNutritifs($dbaretchef->getApportsNutritifs('apports_nutritifs'));
        $preferredRecipe->setPhoto($dbaretchef->getPhoto('photo'));
        $preferredRecipe->setVideo($dbaretchef->getVideo('video'));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($preferredRecipe);
        $entityManager->flush();
        return $this->json(['message' => 'Recipe added to preferred list']);
    }
    #[Route('/dbaretchef/{id}/delete-preferred', name: 'deletedbaraprefere',methods: ["DELETE"])]
    public function deletedbaraprefere(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $preferredRecipe = $entityManager->getRepository(DbartiElPrefere::class)->find($id);
        if (!$preferredRecipe) {
            return $this->json('No Dbara Prefere found for id' . $id, 404);
        }
        $entityManager->remove($preferredRecipe);
        $entityManager->flush();

        return $this->json('Deleted a Dbara Prefere successfully with id ' . $id);
    }
    #[Route('/getdbartiprefere', name: 'get_dbartiprefere',methods: ["GET"])]
    public function getdbaretiprefere():Response
    {
        $products = $this->getDoctrine()
            ->getRepository(DbartiElPrefere::class)
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
                'nom_chef' => $product->getNomChef(),
            ];
        }
        return $this->json($data);
    }
    #[Route('/showdbartiprefere/{id}', name: 'show_dbartiprefere',methods: ["GET"])]
    public function showdbaretiprefere(int $id): Response
    {
        $dbaretipref = $this->getDoctrine()->getRepository(DbartiElPrefere::class)->find($id);
        if (!$dbaretipref) {

            return $this->json('No Dbaretchef found for id' . $id, 404);
        }
        $data[] = [
            'id' => $dbaretipref->getId(),
            'type' => $dbaretipref->getType(),
            'nom' => $dbaretipref->getNom(),
            'description' => $dbaretipref->getDescription(),
            'temps_prepartion'=>$dbaretipref->getTempsPreparation(),
            'nombre_ingredient'=>$dbaretipref->getNombreIngredient(),
            'niv_difficulte'=>$dbaretipref->getNivDifficulte(),
            'ingredients'=>$dbaretipref->getIngredients(),
            'apports_nutritifs'=>$dbaretipref->getApportsNutritifs(),
            'photo'=>$dbaretipref->getPhoto(),
            'video'=>$dbaretipref->getVideo(),
            'nom_chef' => $dbaretipref->getNomChef(),
        ];
        return $this->json($data);
    }
}
