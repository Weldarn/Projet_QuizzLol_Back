<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;

class UserController extends AbstractController
{
    private Security $security;
    private EntityManagerInterface $entityManager;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    #[Route("/api/user/delete", name: "api_user_delete", methods: ["DELETE"])]
    public function deleteUser(): Response
    {
        $user = $this->security->getUser();
        if (!$user) {
            throw new AccessDeniedException('You are not authenticated.');
        }

        // Ici, vous pouvez ajouter une logique supplémentaire pour vérifier si l'utilisateur a confirmé la suppression de son compte.

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        // Après la suppression, vous pouvez retourner une réponse ou rediriger l'utilisateur vers une autre page.
        return $this->json(['message' => 'User deleted successfully']);
    }
}
