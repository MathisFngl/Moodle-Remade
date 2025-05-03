<?php

namespace App\Controller;

use App\Repository\CoursRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(CoursRepository $coursRepository, UtilisateurRepository $utilisateurRepository): Response
    {
        return $this->render('login.html.twig', [
            'nb_uv' => $coursRepository->count([]),
            'nb_utilisateurs' => $utilisateurRepository->count([]),
            'nb_etudiants' => $utilisateurRepository->countByRole('ROLE_ETUDIANT'),
            'nb_profs' => $utilisateurRepository->countByRole('ROLE_PROFESSEUR'),
        ]);
    }
}
