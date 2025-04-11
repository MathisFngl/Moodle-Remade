<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_dashboard')]
    public function index(): Response
    {
        $ues = [
            ["code" => "INF1", "nom" => "Informatique 1", "desc" => "Introduction à la programmation.", "image" => "path_to_image_1.jpg"],
            ["code" => "MAT2", "nom" => "Mathématiques 2", "desc" => "Algèbre et analyse avancées.", "image" => "path_to_image_2.jpg"],
            ["code" => "PHY3", "nom" => "Physique 3", "desc" => "Mécanique et thermodynamique.", "image" => "path_to_image_3.jpg"],
            ["code" => "CHM4", "nom" => "Chimie 4", "desc" => "Chimie organique et inorganique.", "image" => "path_to_image_4.jpg"],
            ["code" => "ELE5", "nom" => "Électronique 5", "desc" => "Circuits et systèmes électroniques.", "image" => "path_to_image_5.jpg"],
        ];

        $users = [
            ["prenom" => "Alice", "nom" => "Dupont", "email" => "alice.dupont@utbm.fr", "role" => "étudiant", "admin" => false],
            ["prenom" => "Bob", "nom" => "Martin", "email" => "bob.martin@utbm.fr", "role" => "prof", "admin" => true],
            ["prenom" => "Charlie", "nom" => "Lemoine", "email" => "charlie.lemoine@utbm.fr", "role" => "admin", "admin" => true],
            ["prenom" => "David", "nom" => "Bernard", "email" => "david.bernard@utbm.fr", "role" => "prof", "admin" => false],
            ["prenom" => "Emma", "nom" => "Rousseau", "email" => "emma.rousseau@utbm.fr", "role" => "étudiant", "admin" => false],
        ];

        return $this->render('admin/index.html.twig', [
            'ues' => $ues,
            'users' => $users,
        ]);
    }
}