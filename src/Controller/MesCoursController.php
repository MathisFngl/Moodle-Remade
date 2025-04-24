<?php

namespace App\Controller;

use App\Entity\Cours;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MesCoursController extends AbstractController
{
    #[Route('/mes-cours', name: 'mes_cours')]
    public function index(EntityManagerInterface $em): Response
    {
        $cours = $em->getRepository(Cours::class)->findAll();

        return $this->render('mes_cours.html.twig', [
            'cours_list' => $cours
        ]);
    }
}