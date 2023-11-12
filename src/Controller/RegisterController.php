<?php
namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
         $jsonRecu = json_decode($request->getContent());
         $username = $jsonRecu->username ?? null;
         $password = $jsonRecu->password ?? null;

         if (!$username || !$password) {
            return $this->json([
                'message' => 'Invalid username or password',
            ], Response::HTTP_BAD_REQUEST);
        }

        // Crée un nouvel utilisateur et défini ses propriétés
        $user = new User();
        $user->setUsername($username);

        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            "$password"
        );
        $user->setPassword($hashedPassword);

        // Valide l'entité de l'utilisateur
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            // Gérer les erreurs de validation ici
            return $this->json([
                'errors' => (string) $errors,
            ], Response::HTTP_BAD_REQUEST);
        }

        // Enregistre l'utilisateur dans la bdd
        $entityManager->persist($user);
        $entityManager->flush();

        // Redirige ou retourne une réponse JSON
        return $this->json([
            'user' => [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
            ],
        ], Response::HTTP_CREATED);
    }
}