<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MesCoursController extends AbstractController
{
    #[Route('/mes-cours', name: 'mes_cours')]
    public function index(): Response
    {
        return $this->render('mes_cours.html.twig');
    }
}