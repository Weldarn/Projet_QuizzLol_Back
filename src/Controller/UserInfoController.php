<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class UserInfoController extends AbstractController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/api/user_info', name: 'api_user_info', methods: ['GET'])]
    public function userInfo(): Response
    {
        $user = $this->security->getUser();

        if (!$user) {
            return $this->json([
                'message' => 'User not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'username' => $user->getUsername(),
            // Ajoutez ici d'autres informations de l'utilisateur que vous souhaitez renvoyer
        ]);
    }
}