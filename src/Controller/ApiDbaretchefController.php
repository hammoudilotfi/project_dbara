<?php

namespace App\Controller;

use App\Entity\Dbaretelchef;
use App\Entity\DbartiElPrefere;
use App\Entity\Subcategory;
use App\Repository\DbaretelchefRepository;
use App\Repository\SubcategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use JMS\Serializer\Annotation\Groups;

#[Route('/api', name: 'api_')]
class ApiDbaretchefController extends AbstractController
{
    public function __construct(SubcategoryRepository $SubcategoryRepository, SluggerInterface $slugger)
    {
        $this->SubcategoryRepository = $SubcategoryRepository;
        $this->slugger = $slugger;
    }

    #[Route('/ajoutbaretchef', name: 'ajoutbaretchef', methods: ['POST', 'GET'])]
    public function adddbaretchef(Request $request, EntityManagerInterface $entityManager): Response
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
        $recettechef = new Dbaretelchef();
        $recettechef->setType($request->request->get('type'));
        $recettechef->setNom($request->request->get('nom'));
        $recettechef->setDescription($request->request->get('description'));
        $recettechef->setTempsPreparation($request->request->get('temps_preparation'));
        $recettechef->setNombreIngredient($request->request->get('nombre_ingredient'));
        $recettechef->setNivDifficulte($request->request->get('niv_difficulte'));
        $recettechef->setIngredients($request->request->get('ingredients'));
        $recettechef->setApportsNutritifs($request->request->get('apports_nutritifs'));
        $recettechef->setPhoto($photoFileName);
        $recettechef->setVideo($videoFileName);
        $recettechef->setNomChef($request->request->get('nom_chef'));
        $recettechef->setSubcategoryNom($request->request->get('subcategory_nom'));
        // Init subcategory-id
        $subcategory_id = (int) $request->request->get('subcategory_id');

        if ($subcategory_id) {
            $subcatecory = $this->getDoctrine()->getRepository(Subcategory::class)->find($subcategory_id);
            if (!empty($subcatecory)) {
                $recettechef->setSubcategory($subcatecory);
            }
        }
        $entityManager->persist($recettechef);
        $entityManager->flush();
        return $this->json('Created new recette chef successfully with id ' . $recettechef->getId());
    }


    #[Route('/ajoutbaretchef/upload-file', name: 'ajoutbaretchef_upload_file', methods: ['POST'])]
    public function addFile(Request $request): JsonResponse
    {
        $uploadedFile = $request->files->get('file');
        $fileDirectory = '/public/uploads/photos';
        $fileName = md5(uniqid()) . '.' . $uploadedFile->getClientOriginalExtension();
        $uploadedFile->move($fileDirectory, $fileName);

        return new JsonResponse(['file_name' => $fileName]);
    }
    #[Route('/getdbaretchef', name: 'getdbaretchef',methods: ["GET"])]
    public function getdbaretchef():Response
    {
        $products = $this->getDoctrine()
            ->getRepository(Dbaretelchef::class)
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
                'subcategory_nom' => $product->getSubcategoryNom(),
            ];
        }
        return $this->json($data);
    }

    #[Route('/showdbaretchef/{id}', name: 'showdbaretchef',methods: ["GET"])]
    public function showdbaretchef(int $id): Response
    {
        // dd($id);
        $dbaretchef = $this->getDoctrine()->getRepository(Dbaretelchef::class)->find($id);
        if (!$dbaretchef) {

            return $this->json('No Dbaretchef found for id' . $id, 404);
        }
        $data[] = [
            'id' => $dbaretchef->getId(),
            'type' => $dbaretchef->getType(),
            'nom' => $dbaretchef->getNom(),
            'description' => $dbaretchef->getDescription(),
            'temps_prepartion'=>$dbaretchef->getTempsPreparation(),
            'nombre_ingredient'=>$dbaretchef->getNombreIngredient(),
            'niv_difficulte'=>$dbaretchef->getNivDifficulte(),
            'ingredients'=>$dbaretchef->getIngredients(),
            'apports_nutritifs'=>$dbaretchef->getApportsNutritifs(),
            'photo'=>$dbaretchef->getPhoto(),
            'video'=>$dbaretchef->getVideo(),
            'nom_chef' => $dbaretchef->getNomChef(),
            'subcategory_nom' => $dbaretchef->getSubcategoryNom(),
        ];
        return $this->json($data);
    }
    #[Route('/searchdbaretchef/{nom}', name: 'searchdbaretchef', methods: ['GET'])]
    public function searchByName(Request $request, DbaretelchefRepository $dbaretelchefRepository,string $nom): Response
    {
        $dbaretchefs = $dbaretelchefRepository->findDbaretchByName($nom);
        if (empty($dbaretchefs)) {
            return $this->json('No Dbaretchef found with name: ' . $nom, 404);
        }
        $data = [];
        foreach ($dbaretchefs as $dbaretchef) {
            $data[] = [
                'id' => $dbaretchef->getId(),
                'type' => $dbaretchef->getType(),
                'nom' => $dbaretchef->getNom(),
                'description' => $dbaretchef->getDescription(),
                'temps_prepartion' => $dbaretchef->getTempsPreparation(),
                'nombre_ingredient' => $dbaretchef->getNombreIngredient(),
                'niv_difficulte' => $dbaretchef->getNivDifficulte(),
                'ingredients' => $dbaretchef->getIngredients(),
                'apports_nutritifs' => $dbaretchef->getApportsNutritifs(),
                'photo' => $dbaretchef->getPhoto(),
                'video' => $dbaretchef->getVideo(),
                'nom_chef' => $dbaretchef->getNomChef(),
                'subcategory_nom' => $dbaretchef->getSubcategoryNom(),
            ];
        }
        return $this->json($data);
    }
    #[Route('/editdbaretchef/{id}', name: 'editdbaretchef',methods: ["PUT"])]
    public function editdbaretchef(Request $request,int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $dbaretchef = $entityManager->getRepository(Dbaretelchef::class)->find($id);
        if (!$dbaretchef) {
            return $this->json('No Dbaret chef found for id' . $id, 404);
        }
        $dbaretchef->setType($request->request->get('type'));
        $dbaretchef->setNom($request->request->get('nom'));
        $dbaretchef->setDescription($request->request->get('description'));;
        $dbaretchef->setTempsPreparation($request->request->get('temps_preparation'));
        $dbaretchef->setNombreIngredient($request->request->get('nombre_ingredient'));
        $dbaretchef->setNivDifficulte($request->request->get('niv_difficulte'));
        $dbaretchef->setIngredients($request->request->get('ingredients'));
        $dbaretchef->setApportsNutritifs($request->request->get('apports_nutritifs'));
        $dbaretchef->setPhoto($request->request->get('photo'));
        $dbaretchef->setVideo($request->request->get('video'));
        $dbaretchef->setSubcategoryNom($request->request->get('subcategory_nom'));
        $dbaretchef->flush();
        $data[] = [
            'id' => $dbaretchef->getId(),
            'type' => $dbaretchef->getType(),
            'nom' => $dbaretchef->getNom(),
            'description' => $dbaretchef->getDescription(),
            'temps_prepartion'=>$dbaretchef->getTempsPreparation(),
            'nombre_ingredient'=>$dbaretchef->getNombreIngredient(),
            'niv_difficulte'=>$dbaretchef->getNivDifficulte(),
            'ingredients'=>$dbaretchef->getIngredients(),
            'apports_nutritifs'=>$dbaretchef->getApportsNutritifs(),
            'photo'=>$dbaretchef->getPhoto(),
            'video'=>$dbaretchef->getVideo(),
            'nom_chef' => $dbaretchef->getNomChef(),
            'subcategory_nom' => $dbaretchef->getSubcategoryNom(),
        ];
        return $this->json($data);

    }
    #[Route('/deletedbaretchef/{id}', name: 'deletedbaretchef',methods: ["DELETE"])]
    public function deletedbaretchef(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $dbaretchef = $entityManager->getRepository(Dbaretelchef::class)->find($id);
        if (!$dbaretchef) {
            return $this->json('No Dbaret chef found for id' . $id, 404);
        }
        $entityManager->remove($dbaretchef);
        $entityManager->flush();

        return $this->json('Deleted a Dbaret chef successfully with id ' . $id);
    }

    #[Route('/getrecettebysubcategory/{subcategoryId}', name: 'recettes_by_subcategory',methods: ["GET"])]
    public function getRecetteBySubcategory(DbaretelchefRepository $repository, int $subcategoryId): Response
    {
        $dbaretchefs = $repository->findRecetteBySubcategoryId($subcategoryId);

        if (empty($dbaretchefs)) {
            return $this->json('No Dbaretchef found for subcategory ID ' . $subcategoryId, 404);
        }

        $data = [];
        foreach ($dbaretchefs as $dbaretchef) {
            $data[] = [
                'id' => $dbaretchef->getId(),
                'type' => $dbaretchef->getType(),
                'nom' => $dbaretchef->getNom(),
                'description' => $dbaretchef->getDescription(),
                'temps_prepartion' => $dbaretchef->getTempsPreparation(),
                'nombre_ingredient' => $dbaretchef->getNombreIngredient(),
                'niv_difficulte' => $dbaretchef->getNivDifficulte(),
                'ingredients' => $dbaretchef->getIngredients(),
                'apports_nutritifs' => $dbaretchef->getApportsNutritifs(),
                'photo' => $dbaretchef->getPhoto(),
                'video' => $dbaretchef->getVideo(),
                'nom_chef' => $dbaretchef->getNomChef(),
                'subcategory_nom' => $dbaretchef->getSubcategoryNom(),
            ];
        }
        return $this->json($data);
    }

}
