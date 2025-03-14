<?php

namespace App\Controller;

use App\Entity\Dbaretelchef;
use App\Entity\Recette;
use App\Entity\Savednote;
use App\Repository\RecetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]

class ApiSavednoteController extends AbstractController
{
    private $RecetteRepository;
    public function __construct(RecetteRepository $RecetteRepository)
    {
        $this->RecetteRepository = $RecetteRepository;
    }
    #[Route('/getsavednote', name: 'getsavednote',methods: ["GET"])]
    public function getsavednote(): Response
    {
        $products = $this->getDoctrine()
            ->getRepository(Savednote::class)
            ->findAll();
        $data = [];
        foreach ($products as $product) {
            $data[] = [
                'id' => $product->getId(),
                'note' => $product->getNote(),
            ];
        }
        return $this->json($data);
    }
    #[Route('/addnote', name: 'add_note', methods: ['POST'])]
    public function addNote(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $dbaretchefId = (int)$request->request->get('dbaretchef_id');
        $noteValue = $request->request->get('note');
        if (!is_numeric($noteValue) || $noteValue < 1 || $noteValue > 10) {
            return $this->json('Note must be a number between 1 and 10.', 400);
        }
        $dbaretchef = $entityManager->getRepository(Dbaretelchef::class)->find($dbaretchefId);
        if (!$dbaretchef) {
            return $this->json(['message' => 'Dbaretelchef not found'], 404);
        }
        $existingNote = $entityManager->getRepository(Savednote::class)->findOneBy([
            'dbaretelchef' => $dbaretchef,
            'abonnees' => $this->getUser(),
        ]);
        if ($existingNote) {
            return $this->json(['message' => 'You have already given a note for this dbaretchef'], 400);
        }
        $note = new Savednote();
        $note->setNote($noteValue);
        $note->setDbaretelchef($dbaretchef);
        $note->setAbonnees($this->getUser());
        $entityManager->persist($note);
        $entityManager->flush();
        return $this->json(['message' => 'Note added successfully']);
    }

    #[Route('/showsavednote/{id}', name: 'showsavednote',methods: ["GET"])]
    public function showsavednote(int $id): Response
    {
        $savednote = $this->getDoctrine()->getRepository(Savednote::class)->find($id);
        if (!$savednote) {

            return $this->json('No savednote found for id' . $id, 404);
        }
        $data[] = [
            'id' => $savednote->getId(),
            'note' => $savednote->getNote(),
        ];
        return $this->json($data);
    }
    #[Route('/editsavednote/{id}', name: 'editsavednote',methods: ["PUT"])]
    public function editsavednote(Request $request,int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $savednote = $entityManager->getRepository(Savednote::class)->find($id);
        if (!$savednote) {
            return $this->json('No savednote found for id' . $id, 404);
        }
        $savednote->setNote($request->request->get('note'));
        $entityManager->flush();
        $data[] = [
            'id' => $savednote->getId(),
            'note' => $savednote->getNote(),
        ];
        return $this->json($data);
    }
    #[Route('/deletesavednote/{id}', name: 'deletesavednotevote',methods: ["DELETE"])]
    public function deletesavednote(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $savednote = $entityManager->getRepository(Savednote::class)->find($id);
        if (!$savednote) {
            return $this->json('No savednote found for id' . $id, 404);
        }
        $entityManager->remove($savednote);
        $entityManager->flush();

        return $this->json('Deleted a savednote successfully with id ' . $id);
    }
    //calcule le moyen de note pour une recette
    #[Route('/recette/{id}/average-score', name: 'average-score',methods: ["POST"])]
    public function calculateAverageScore(Recette $recette): Response
    {
        $notes = $recette->getSavednotes();
        $totalScores = 0;
        $totalNotes = count($notes);
        foreach ($notes as $note) {
            $totalScores += $note->getNote();
        }
        $averageScore = $totalNotes > 0 ? $totalScores / $totalNotes : 0;
        return $this->json(['average_score' => $averageScore]);
    }
}
