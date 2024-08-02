<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherAwareInterface ;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class RegisterController extends AbstractController
{ 
    
    // Constructeur pour initialiser les services nécessaires
    public function __construct(private EntityManagerInterface $manager, private UserRepository $userRepository )
    {
    
    }
    /*Cette méthode gère l'enregistrement des nouveaux utilisateurs.
     * Elle vérifie si l'email existe déjà, valide les données et crée un nouvel utilisateur.*/
    #[Route('/api/register', name: 'register', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $email = $data['email'];
        $password = $data['password'];
        $firstName=$data['firstName'];
        $user = new User();

        $email_exist= $this->userRepository->findOneBy(['Email' => $email]);

        if($email_exist)
        {   
            return new JsonResponse(
                ['message' => 'Cet  utilisaeur  existe deja, Voulez vous vous connecter ?'],
                Response::HTTP_BAD_REQUEST
            );
            
            }
        if (!$email || !$password || !$firstName) {
            return new JsonResponse(['message' => 'Email firstname and  password are required'], Response::HTTP_BAD_REQUEST);
        }

        $user = new user();
        $user->setEmail($email);
        $user->setFirstName($firstName);
        $hashedPassword = $passwordHasher->hashPassword($user , $password);
        $user->setPassword($hashedPassword);

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Utilisateur  enregistrer avec  succes'], Response::HTTP_CREATED);
    }

  

}
