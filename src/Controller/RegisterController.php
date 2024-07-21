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

        // Extraire les champs requis du JSON
        $email = $data['email'];
        $password = $data['password'];
        $firstName=$data['firstName'];
        $user = new User();

        $email_exist= $this->userRepository->findOneBy(['Email' => $email]);

        if($email_exist)
        {   
            return new JsonResponse(
                ['message' => 'Cet email existe deja, veuillez le changer'],
                Response::HTTP_BAD_REQUEST
            );
            
            }
            // Vérifier que tous les champs nécessaires sont tous  présents
        if (!$email || !$password || !$firstName) {
            return new JsonResponse(['message' => 'Email firstname and  password are required'], Response::HTTP_BAD_REQUEST);
        }
        // Créer un nouvel utilisateur
        $user = new user();
        $user->setEmail($email);
        $user->setFirstName($firstName);
        $hashedPassword = $passwordHasher->hashPassword($user , $password);
        $user->setPassword($hashedPassword);

        $entityManager->persist($user);// Persister l'utilisateur dans la base de données
        $entityManager->flush(); // Enregistrer les modifications
        return new JsonResponse(['message' => 'Utilisateur  enregistrer avec  succes'], Response::HTTP_CREATED);
    }

  

}
