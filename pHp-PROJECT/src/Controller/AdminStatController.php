<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminStatController extends AbstractController
{
    #[Route('/admin/stat', name: 'app_admin_stat')]
    public function index(): Response
    {
        return $this->render('admin_stat/index.html.twig', [
            'controller_name' => 'AdminStatController',
        ]);
    }
}
