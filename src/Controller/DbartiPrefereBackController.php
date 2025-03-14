<?php

namespace App\Controller;

use App\Entity\Dbaretchefback;
use App\Entity\Dbaretelchef;
use App\Entity\DbartElPrefereBack;
use App\Entity\DbartiElPrefere;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class DbartiPrefereBackController extends AbstractController
{
    #[Route('/dbaretchefback/{id}/ajout-to-preferred', name: 'ajout_to_preferred',methods: ["POST"])]
    public function addToPreferredback(Request $request, $id): Response
    {
        $dbaretchef = $this->getDoctrine()->getRepository(Dbaretchefback::class)->find($id);
        if (!$dbaretchef) {

            return $this->json('No Dbaretchef found for id' . $id, 404);
        }
        $preferredRecipe = new DbartElPrefereBack();
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
}
