<?php

namespace App\Controller;

use App\Entity\Abonnes;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]

class ApiAbonnesController extends AbstractController
{
    #[Route('/addabonnes', name: 'addabonnes', methods: ["POST"])]
    public function addabonnes(Request $request, EntityManagerInterface $entityManager): Response
    {
        $abonnes = new Abonnes();
        $abonnes->setNum($request->request->get('num'));
        $abonnes->setStatus($request->request->get('status'));
        $abonnes->setDateInscri( new \DateTime());
        $abonnes->setDateDesinscri (new \DateTime());
        $abonnes->setLastLogin(new \DateTime());
        $abonnes->setNom($request->request->get('nom'));
        $abonnes->setPrenom($request->request->get('prenom'));
        $abonnes->setPhoto($request->request->get('photo'));
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($abonnes);
        $entityManager->flush();
        return $this->json('Created new abonnes successfully with id ' . $abonnes->getId());
    }
    #[Route('/abonnes/register', name: 'register', methods: ["POST"])]
    public function register(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $verificationCode = $this->generateVerificationCode();
        // Send the verification code via SMS (using your chosen SMS service provider)
        $smsService = $this->get('app.sms_service'); // Replace 'app.sms_service' with your service ID
        $smsService->sendVerificationCode($data['num'], $verificationCode);

        // Save the mobile number and verification code in the database
        $abonne = new Abonnes();
        $abonne->setNum($data['num']);
        $abonne->setVerificationCode($verificationCode);
        $abonne->setStatus(false); // Set the status as inactive until verified
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($abonne);
        $entityManager->flush();
        return new Response('Verification code sent successfully', Response::HTTP_OK);
    }
    #[Route('/abonnes/verify', name: 'addabonnes', methods: ["POST"])]
    public function verify(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        // Retrieve the user by mobile number from the database
        $repository = $this->getDoctrine()->getRepository(Abonnes::class);
        $abonne = $repository->findOneBy(['mobileNumber' => $data['mobileNumber']]);

        if (!$abonne) {
            return new Response('Mobile number not found', Response::HTTP_NOT_FOUND);
        }

        // Check if the verification code matches
        if ($abonne->getVerificationCode() === $data['verificationCode']) {
            // Update the subscription status to active
            $abonne->setSubscriptionStatus(true);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($abonne);
            $entityManager->flush();

            return new Response('Verification successful. Subscription activated.', Response::HTTP_OK);
        } else {
            return new Response('Invalid verification code', Response::HTTP_BAD_REQUEST);
        }
    }

    // Helper function to generate a random verification code
    private function generateVerificationCode()
    {
        return rand(1000, 9999); // Generate a 4-digit verification code (you can adjust the range as needed)
    }


    #[Route('/getabonnes', name: 'getabonnes', methods: ["GET"])]
    public function getAbonnes(): Response
    {
        $products = $this->getDoctrine()
            ->getRepository(Abonnes::class)
            ->findAll();
        $data = [];
        foreach ($products as $product) {
            $data[] = [
                'id' => $product->getId(),
                'num' => $product->getNum(),
                'status' => $product->getStatus(),
                'dateinscri' => $product->getDateInscri(),
                'datedesinscri' => $product->getDateDesinscri(),
                'lastlogin' => $product->getLastlogin(),
                'nom' => $product->getNom(),
                'prenom' => $product->getPrenom(),
                'photo' => $product->getPhoto(),
            ];
        }
        return $this->json($data);
    }
    #[Route('/showabonnes/{id}', name: 'showabonnes',methods: ["GET"])]
    public function showabonnes(int $id): Response
    {
        $abonnes = $this->getDoctrine()->getRepository(Abonnes::class)->find($id);
        if (!$abonnes) {

            return $this->json('No Abonnes found for id' . $id, 404);
        }
        $data[] = [
            'id' => $abonnes->getId(),
            'num' => $abonnes->getNum(),
            'status' => $abonnes->getStatus(),
            'dateinscri' => $abonnes->getDateInscri(),
            'datedesinscri' => $abonnes->getDateDesinscri(),
            'lastlogin' => $abonnes->getLastlogin(),
            'nom' => $abonnes->getNom(),
            'prenom' => $abonnes->getPrenom(),
            'photo' => $abonnes->getPhoto(),
        ];
        return $this->json($data);
    }
    #[Route('/editabonnes/{id}', name: 'editabonnes',methods: ["PUT"])]
    public function editAbonnes(Request $request,int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $abonnes = $entityManager->getRepository(Abonnes::class)->find($id);
        if (!$abonnes) {
            return $this->json('No Abonnes found for id' . $id, 404);
        }
        $abonnes->setNum($request->request->get('num'));
        $abonnes->setStatus($request->request->get('status'));
        $abonnes->setDateInscri(new \DateTime());
        $abonnes->setDateDesinscri(new \DateTime());
        $abonnes->setLastLogin(new \DateTime());
        $abonnes->setNom($request->request->get('nom'));
        $abonnes->setPrenom($request->request->get('prenom'));
        $abonnes->setPhoto($request->request->get('photo'));
        $entityManager->flush();
        $data[] = [
            'id' => $abonnes->getId(),
            'num' => $abonnes->getNum(),
            'status' => $abonnes->getStatus(),
            'dateinscri' => $abonnes->getDateInscri(),
            'datedesinscri' => $abonnes->getDateDesinscri(),
            'lastlogin' => $abonnes->getLastlogin(),
            'nom' => $abonnes->getNom(),
            'prenom' => $abonnes->getPrenom(),
            'photo' => $abonnes->getPhoto(),
        ];
        return $this->json($data);
    }
    #[Route('/deleteabonnes/{id}', name: 'deleteabonnes',methods: ["DELETE"])]
    public function deleteAbonnes(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $abonnes = $entityManager->getRepository(Abonnes::class)->find($id);
        if (!$abonnes) {
            return $this->json('No Abonnes found for id' . $id, 404);
        }
        $entityManager->remove($abonnes);
        $entityManager->flush();

        return $this->json('Deleted a Abonnes successfully with id ' . $id);
    }
}
