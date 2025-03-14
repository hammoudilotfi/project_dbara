<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Security;
use Swift_Mailer;

#[Route('/api', name: 'api_')]
class HomeController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    #[Route('/register', name: 'app_register', methods: ['POST'])]
    public function index(Request $request, UserPasswordEncoderInterface $encoder, ValidatorInterface $validator): Response
    {
        $decoded = json_decode($request->getContent(), true);
        if (!$decoded) {
            return $this->json(['message' => 'Invalid JSON data'], 400);
        }

        $email = $decoded['email'] ?? null;
        $password = $decoded['password'] ?? null;
        $tel = $decoded['tel'] ?? null; // Moved the definition here
        $pin = $decoded['pin'] ?? null;
        $nom = $decoded['nom'] ?? null;
        $prenom = $decoded['prenom'] ?? null;
        $sexe = $decoded['sexe'] ?? null;
        $resetTokenExpiration = new \DateTime();
        $resetTokenExpiration->add(new \DateInterval('PT1H'));
        $constraints = new Assert\Collection([
            'fields' => [
                'email' => new Assert\Email(['message' => 'Invalid email format']),
                'password' => new Assert\NotBlank(['message' => 'Password cannot be blank']),
                'tel' => new Assert\Regex([
                    'pattern' => '/^5\d{7}$/',
                    'message' => 'The phone number must start with "5" and have 8 digits',
                ]),
                'pin' => new Assert\NotBlank(['message' => 'PIN cannot be blank']),
                'nom' => new Assert\NotBlank(['message' => 'Nom cannot be blank']),
                'prenom' => new Assert\NotBlank(['message' => 'Prenom cannot be blank']),
                'sexe' => new Assert\NotBlank(['message' => 'Sexe cannot be blank']),
            ],
        ]);
        $violations = $validator->validate($decoded, $constraints);
        if (count($violations) > 0) {
            return $this->json(['message' => (string) $violations], 400);
        }
        $user = new User();
        $user->setPassword($encoder->encodePassword($user, $password));
        $user->setEmail($email);
        $user->setTel($tel);
        $user->setPin($pin);
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setSexe($sexe);
        $user->setResetTokenExpiration(new \DateTime('+1 hour'));
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return $this->json(['message' => 'Registered Successfully']);
    }
   #[Route('/login_check', name: 'login',methods: ["POST"])]
    public function testLogin(){

        $user = static::createClient();
       $user->request('POST', '/api/login_check', [], [],
           [
               'Content-Type' => 'application/json',
               'Accept' => 'application/json'
           ],
           json_encode([
               'username' => '50431123',
               'password' => 'password123'
           ])
       );
        $this->assertEquals(200, $user->getResponse()->getStatusCode());
    }

    #[Route('/getusers', name: 'users_show_all',methods: ["GET"])]
    public function showAllUsers(): Response
    {
        $products = $this->getDoctrine()->getRepository(User::class)->findAll();
        $data =[];
        foreach ($products as $product) {
            $data[] = [
                'id' => $product->getId(),
                'nom' => $product->getNom(),
                'prenom' => $product->getPrenom(),
                'tel'=>$product->getTel(),
                'pin'=>$product->getPin(),
                'sexe'=>$product->getSexe(),
                'email'=>$product->getEmail(),
            ];
        }
        return $this->json($data);

    }
    #[Route('/showuser/{id}', name: 'user_id_show',methods: ["GET"])]
    public function showabonnes(int $id): Response
    {
        $users = $this->getDoctrine()->getRepository(User::class)->find($id);
        if (!$users) {

            return $this->json('No User found for id' . $id, 404);
        }
        $data[] = [
            'id' => $users->getId(),
            'nom' => $users->getNom(),
            'prenom' => $users->getPrenom(),
            'tel'=>$users->getTel(),
            'pin'=>$users->getPin(),
            'sexe'=>$users->getSexe(),
            'email'=>$users->getEmail(),
        ];
        return $this->json($data);
    }
    #[Route('/updateuser/{id}', name: 'user_update',methods: ["PUT"])]
    public function updateUser(Request $request,int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);
        if (!$user) {
            return $this->json('No User found for id' . $id, 404);
        }
        $user->setPassword($request->request->get('password'));
        $user->setEmail($request->request->get('email'));
        $user->setTel($request->request->get('tel'));
        $user->setPin($request->request->get('pin'));
        $user->setNom($request->request->get('nom'));
        $user->setPrenom($request->request->get('prenom'));
        $user->setSexe($request->request->get('sexe'));
        $entityManager->flush();
        $data[] = [
            'id' => $user->getId(),
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'tel'=>$user->getTel(),
            'pin'=>$user->getPin(),
            'sexe'=>$user->getSexe(),
            'email'=>$user->getEmail(),
        ];
        return $this->json($data);
    }
    #[Route('/reset-password', name: 'reset_password', methods: ['POST'])]
    public function resetPassword(Request $request, Swift_Mailer $mailer, UserPasswordEncoderInterface $encoder): Response
    {
        $requestData = json_decode($request->getContent(), true);
        $newPassword = $requestData['new_password'] ?? null;
        if (!$newPassword) {
            return $this->json(['message' => 'Missing new password'], 400);
        }
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['message' => 'User not found'], 404);
        }
        $encodedPassword = $encoder->encodePassword($user, $newPassword);
        $user->setPassword($encodedPassword);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        $message = (new \Swift_Message('Password Changed'))
            ->setFrom('lotfihammoudi384@gmail.com') // Change to your email
            ->setTo($user->getEmail())
            ->setBody(
                "Your password has been successfully changed. Your new password is: $newPassword",
                'text/plain'
            );
        $mailer->send($message);
        return $this->json(['message' => 'Password reset successful']);
    }
    #[Route('/get_user', name: 'api_get_user', methods: ['GET'])]
    public function getUserData(Security $security): Response
    {
        $user = $security->getUser();
        if (!$user) {
            return $this->json(['message' => 'User not authenticated'], 401);
        }
        $userData = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'tel'=>$user->getTel(),
            'pin'=>$user->getPin(),
            'sexe'=>$user->getSexe(),
        ];
        return $this->json($userData);
    }
}
