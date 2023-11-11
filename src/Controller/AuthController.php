<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    #[Route('/logout', name: 'api_logout', methods: ['GET'])]
    public function logout()
    {
        throw new \Exception('This should never be reached!');
    }
}