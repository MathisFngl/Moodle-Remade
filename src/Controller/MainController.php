<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    // Affiche la page d'accueil du site
    #[Route('/', name: 'homepage')]
    public function homepage(): Response
    {
        // Charge et affiche le fichier de base du site
        return $this->render("base.html.twig");
    }
}
