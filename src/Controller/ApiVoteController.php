<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\User;
use App\Entity\Vote;
use App\Repository\ImageRepository;
use App\Repository\OptionRepository;
use App\Repository\VoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class ApiVoteController extends AbstractController
{
    #[Route('/getvote', name: 'getvote', methods: ["GET"])]
    public function getvote(VoteRepository $voteRepository): Response
    {
        $votes = $voteRepository->findAllWithImage();
        $data = [];
        foreach ($votes as $vote) {
            $image = $vote->getVoteimage();
            $data[] = [
                'id' => $vote->getId(),
                'created_at' => $vote->getCreatedAt(),
                'note' => $vote->getNote(),
                'image' => [

                    'id' => $image ? $image->getId() : null,
                    'filename' => $image ? $image->getFilename() : null,
                ],
            ];
        }
        return $this->json($data);
    }
    /*#[Route('/addvote', name: 'addvote', methods: ['POST'])]
    public function addVote(Request $request, EntityManagerInterface $entityManager, ImageRepository $imageRepository): Response
    {
        $note = $request->request->get('note');
        $imageId = $request->request->get('imageId');
        if ($note === null || $imageId === null) {
            return $this->json('Note and imageId are required.', 400);
        }
        if (!is_numeric($note) || $note < 1 || $note > 10) {
            return $this->json('Note must be a number between 1 and 10.', 400);
        }
        $image = $imageRepository->find($imageId);
        if (!$image) {
            return $this->json('Image not found.', 404);
        }
        $vote = new Vote();
        $vote->setCreatedAt(new \DateTime());
        $vote->setNote($note);
        $vote->setVoteimage($image);
        $entityManager->persist($vote);
        $entityManager->flush();
        return $this->json('Created new vote successfully with id ' . $vote->getId(), 201);
    }*/
    #[Route('/addvote', name: 'addvote', methods: ['POST'])]
    public function addVote(Request $request, EntityManagerInterface $entityManager, ImageRepository $imageRepository): Response
    {
        $user = $this->getUser();
        $note = $request->request->get('note');
        $imageId = $request->request->get('imageId');
        if ($note === null || $imageId === null) {
            return $this->json('Note and imageId are required.', 400);
        }
        if (!is_numeric($note) || $note < 1 || $note > 10) {
            return $this->json('Note must be a number between 1 and 10.', 400);
        }
        $image = $imageRepository->find($imageId);
        if (!$image) {
            return $this->json('Image not found.', 404);
        }
        $existingVote = $entityManager->getRepository(Vote::class)->findOneBy([
            'user' => $user,
            'voteimage' => $image,
        ]);
        if ($existingVote) {
            return $this->json('You have already rated this image.', 400);
        }
        $user = $this->getUser();
        $vote = new Vote();
        $vote->setUser($user);
        $vote->setCreatedAt(new \DateTime());
        $vote->setNote($note);
        $vote->setVoteimage($image);
        $entityManager->persist($vote);
        $entityManager->flush();

        return $this->json('Created new vote successfully with id ' . $vote->getId(), 201);
    }
    #[Route('/showvote/{id}', name: 'showvote',methods: ["GET"])]
    public function showVote(int $id, VoteRepository $voteRepository): Response
    {
        $vote = $voteRepository->findVoteWithImage($id);
        if (!$vote) {
            return $this->json('No vote found for id ' . $id, 404);
        }
        $data = [
            'id' => $vote->getId(),
            'created_at' => $vote->getCreatedAt(),
            'note' => $vote->getNote(),
            'image' => [
                'id' => $vote->getVoteimage()->getId(),
                'filename' => $vote->getVoteimage()->getFilename(),
            ],
        ];
        return $this->json($data);
    }
    #[Route('/editvote/{id}', name: 'editvote',methods: ["PUT"])]
    public function editvote(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $vote = $entityManager->getRepository(Vote::class)->find($id);
        if (!$vote) {
            return $this->json('No vote found for id ' . $id, 404);
        }
        $note = $request->request->get('note');
        if ($note === null || !is_numeric($note) || $note < 1 || $note > 10) {
            return $this->json('Invalid note. Note must be a number between 1 and 10.', 400);
        }
        $vote->setCreatedAt(new \DateTime());
        $vote->setNote($note);
        $imageId = $request->request->get('image_id');
        if ($imageId) {
            $image = $entityManager->getRepository(Image::class)->find($imageId);
            if ($image) {
                $vote->setVoteimage($image);
            } else {
                return $this->json('Image not found for id ' . $imageId, 404);
            }
        }
        $entityManager->flush();
        $data[] = [
            'id' => $vote->getId(),
            'created_at' =>$vote->getCreatedAt(),
            'note' => $vote->getNote(),
            'image' => [
                'id' => $vote->getVoteimage() ? $vote->getVoteimage()->getId() : null,
                'filename' => $vote->getVoteimage() ? $vote->getVoteimage()->getFilename() : null,
            ],
        ];
        return $this->json($data);
    }
    #[Route('/deletevote/{id}', name: 'deletevote',methods: ["DELETE"])]
    public function deletevote(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $vote = $entityManager->getRepository(Vote::class)->find($id);
        if (!$vote) {
            return $this->json('No vote found for id' . $id, 404);
        }
        $entityManager->remove($vote);
        $entityManager->flush();

        return $this->json('Deleted a vote successfully with id ' . $id);
    }

}
