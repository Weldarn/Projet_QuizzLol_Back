<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ProfileController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/profile', name: 'app_profile')]
    public function profile(): Response
    {
        $user = $this->security->getUser();

        return $this->json([
            'username' => $user->getUsername(),
            'scores' => $user->getScores(),
        ]);
    }

    #[Route('/api/user_info', name: 'api_user_info', methods: ['GET'])]
    public function userInfo(): Response
    {
        $user = $this->security->getUser();

        if (!$user) {
            // Retourner une réponse d'erreur si l'utilisateur n'est pas authentifié
            return $this->json(['error' => 'User not authenticated'], Response::HTTP_UNAUTHORIZED);
        }

        // Récupérer et formater les scores de l'utilisateur
        $scores = [];
        foreach ($user->getGameScores() as $gameScore) {
            $scores[] = [
                'scoreChampionEasy' => $gameScore->getScoreChampionEasy(),
                'scoreChampionHard' => $gameScore->getScoreChampionHard(),
                'scoreObject' => $gameScore->getScoreObject(),
            ];
        }

        // Retourner les informations de l'utilisateur
        return $this->json([
            'username' => $user->getUsername(),
            'scores' => $scores,
        ]);
    }
}