<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/mon-profil', name: 'profil')]
    public function index(): Response
    {
        $user = [
            'nom' => 'Gardiol',
            'email' => 'Emilie',
        ];

        return $this->render('profil.html.twig', [
            'user' => $user,
        ]);
    }
}