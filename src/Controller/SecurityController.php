<?php

namespace App\Controller;

use App\Repository\CoursRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    // Gérer l'affichage des informations sur la page de connexion
    #[Route(path: '/login', name: 'app_login')]
    public function login(
        AuthenticationUtils $authenticationUtils,
        CoursRepository $coursRepository,
        UtilisateurRepository $utilisateurRepository
    ): Response
    {

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'nb_uv' => $coursRepository->count([]),
            'nb_utilisateurs' => $utilisateurRepository->count([]),
            'nb_etudiants' => $utilisateurRepository->countByRole('ROLE_ELEVE'),
            'nb_profs' => $utilisateurRepository->countByRole('ROLE_PROFESSEUR'),
        ]);
    }

    // Gérer la déconnexion
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('');
    }
}

