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
    // Affiche tous les cours auxquels l'utilisateur participe
    #[Route('/mes-cours', name: 'mes_cours')]
    public function index(EntityManagerInterface $em): Response
    {
        // Récupération de l'utilisateur connecter
        $user = $this->getUser();

        //  redirection  vers la page de connexion
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Récupération de tous les cours où l'utilisateur est inscrit
        $query = $em->createQuery(
            'SELECT c
             FROM App\Entity\Cours c
             JOIN App\Entity\Participant p WITH p.cours = c
             WHERE p.utilisateur = :user'
        )->setParameter('user', $user);

        $cours = $query->getResult();

        // Affiche la page des cours de l'utilisateur avec la liste de ses cours
        return $this->render('mes_cours.html.twig', [
            'cours_list' => $cours,
        ]);
    }
}
