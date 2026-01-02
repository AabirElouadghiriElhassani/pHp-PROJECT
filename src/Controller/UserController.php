<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/users', name: 'user_index')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig');
    }

    #[Route('/users/{id}', name: 'user_show', requirements: ['id' => '\d+'])]
    public function show(int $id): Response
    {
        return $this->render('user/show.html.twig', [
            'userId' => $id,
        ]);
    }

    #[Route('/dashboard', name: 'user_dashboard')]
    public function dashboard(): Response
    {
        return $this->render('user/dashboard.html.twig');
    }

    #[Route('/profile', name: 'user_profile')]
    public function profile(): Response
    {
        return $this->render('user/profile.html.twig');
    }
}


