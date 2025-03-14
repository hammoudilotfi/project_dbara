<?php

namespace App\Controller;

use App\Entity\Dbaretchefback;
use App\Repository\SubcategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/api', name: 'api_')]
class DbaretchefController extends AbstractController
{
    private $SubcategoryRepository;

    public function __construct(SubcategoryRepository $SubcategoryRepository)
    {
        $this->SubcategoryRepository = $SubcategoryRepository;
    }
    private $slugger;
    public function __construct1(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    #[Route('/recettes/chef', name: 'dbaretchef_add',methods: "POST")]
    public function createDbaretchef(Request $request ,EntityManagerInterface $entityManager,SluggerInterface $slugger): Response
    {
        $dbaretchef = new Dbaretchefback();
        $dbaretchef->setType($request->request->get('type'));
        $dbaretchef->setNom($request->request->get('nom'));
        $dbaretchef->setDescription($request->request->get('description'));
        $dbaretchef->setTempsPreparation($request->request->get('temps_preparation'));
        $dbaretchef->setNivDifficulte($request->request->get('niv_difficulte'));
        $dbaretchef->setNombreIngredient($request->request->get('nombre_ingredient')); $dbaretchef->setApportsNutritifs($request->request->get('apports_nutritifs'));
        $dbaretchef->setIngredients($request->request->get('ingredients'));
        $file = $request->files->get('photo');
        if ($file) {
            $fileName = $this->uploadFile($file, $slugger);
            $dbaretchef->setPhoto($fileName);
        }
        $file = $request->files->get('video');
        if ($file) {
            $fileName = $this->uploadFile($file, $slugger);
            $dbaretchef->setVideo($fileName);
        }
        //init subcategory-id
        $subcatecory_id=(int)$request->request->get('subcategory_id');

        if ($subcatecory_id){
            $subcatecory=$this->SubcategoryRepository->find($subcatecory_id);
            //dd($subcatecory);exit;
            if(!empty($subcatecory)){
                $dbaretchef->setSubcategory($subcatecory);
            }
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($dbaretchef);
        $entityManager->flush();
        return $this->json('Created new recette chef successfully with id ' . $dbaretchef->getId());
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
    #[Route('/showalldbaretchef', name: 'dbaretchef_show_all',methods: ["GET"])]
    public function showalldbaretchef():Response
    {
        $products = $this->getDoctrine()
            ->getRepository(Dbaretchefback::class)
            ->findAll();
        $data = [];
        foreach ($products as $product) {
            $data[] = [
                'id' => $product->getId(),
                'type'=>$product->getType(),
                'nom' => $product->getNom(),
                'description' => $product->getDescription(),
                'temps_prepartion'=>$product->getTempsPreparation(),
                'niv_difficulte'=>$product->getNivDifficulte(),
                'nombre_ingredient'=>$product->getNombreIngredient(),
                'photo'=>$product->getPhoto(),
                'video'=>$product->getVideo(),
            ];
        }
        return $this->json($data);

    }
    #[Route('/getdbaretchef/{id}', name: 'dbaretchef_show',methods: ["GET"])]
    public function getdbaretcheff(int $id): Response
    {
        // dd($id);
        $dbaretchef = $this->getDoctrine()->getRepository(Dbaretchefback::class)->find($id);
        if (!$dbaretchef) {

            return $this->json('No Dbaretchef found for id' . $id, 404);
        }
        $data[] = [
            'id' => $dbaretchef->getId(),
            'type'=>$dbaretchef->getType(),
            'nom' => $dbaretchef->getNom(),
            'description' => $dbaretchef->getDescription(),
            'temps_prepartion'=>$dbaretchef->getTempsPreparation(),
            'niv_difficulte'=>$dbaretchef->getNivDifficulte(),
            'nombre_ingredient'=>$dbaretchef->getNombreIngredient(),
            'photo'=>$dbaretchef->getPhoto(),
            'video'=>$dbaretchef->getVideo(),
        ];
        return $this->json($data);
    }
    #[Route('/updatedbaretchef/{id}', name: 'dbaretchef_update',methods: ["PUT"])]
    public function updatedbaretchef(Request $request,int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $dbaretchef = $entityManager->getRepository(Dbaretchefback::class)->find($id);
        if (!$dbaretchef) {
            return $this->json('No Dbaret chef found for id' . $id, 404);
        }
        $dbaretchef->setType($request->request->get('type'));
        $dbaretchef->setNom($request->request->get('nom'));
        $dbaretchef->setDescription($request->request->get('description'));
        $dbaretchef->setTempsPreparation($request->request->get('temps_preparation'));
        $dbaretchef->setNivDifficulte($request->request->get('niv_difficulte'));
        $dbaretchef->setNombreIngredient($request->request->get('nombre_ingredient'));
        $dbaretchef->setPhoto($request->request->get('photo'));
        $dbaretchef->setVideo($request->request->get('video'));
        $entityManager->flush();
        $data[] = [
            'id' => $dbaretchef->getId(),
            'type'=>$dbaretchef->getType(),
            'nom' => $dbaretchef->getNom(),
            'description' => $dbaretchef->getDescription(),
            'temps_prepartion'=>$dbaretchef->getTempsPreparation(),
            'niv_difficulte'=>$dbaretchef->getNivDifficulte(),
            'nombre_ingredient'=>$dbaretchef->getNombreIngredient(),
            'photo'=>$dbaretchef->getPhoto(),
            'video'=>$dbaretchef->getVideo(),
        ];
        return $this->json($data);
    }
    #[Route('/removedbaretchef/{id}', name: 'dbaretchef_delete',methods: ["DELETE"])]
    public function removedbaretchef(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $dbaretchef = $entityManager->getRepository(Dbaretchefback::class)->find($id);
        if (!$dbaretchef) {
            return $this->json('No Dbaret chef found for id' . $id, 404);
        }
        $entityManager->remove($dbaretchef);
        $entityManager->flush();

        return $this->json('Deleted a Dbaret chef successfully with id ' . $id);
    }
}
