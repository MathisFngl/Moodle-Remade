<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\Participant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MesCoursController extends AbstractController
{
    #[Route('/mes-cours', name: 'mes_cours')]
    public function index(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login'); // Redirect if not logged in
        }

        $query = $em->createQuery(
            'SELECT c
             FROM App\Entity\Cours c
             JOIN App\Entity\Participant p WITH p.cours = c
             WHERE p.utilisateur = :user'
        )->setParameter('user', $user);

        $cours = $query->getResult();

        return $this->render('mes_cours.html.twig', [
            'cours_list' => $cours,
        ]);
    }
}