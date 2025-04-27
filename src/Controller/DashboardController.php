<?php

// src/Controller/DashboardController.php
namespace App\Controller;

use App\Repository\ParticipantRepository;
use App\Repository\MessageRepository;
use App\Repository\UtilisateurRepository; // Import the User repository
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(
        ParticipantRepository $participantRepository,
        MessageRepository $messageRepository,
        UtilisateurRepository $utilisateurRepository
    ): Response
    {
        $user = $this->getUser();
        $participants = $participantRepository->findByUtilisateur($user);

        $cours = [];
        foreach ($participants as $participant) {
            $cours[] = $participant->getCours();
        }

        $messages = [];
        foreach ($cours as $course) {
            $courseMessages = $messageRepository->findBy(
                ['cours_code' => $course->getCode()],
                ['timestamp' => 'DESC'],
                5
            );
            foreach ($courseMessages as $message) {
                $author = $utilisateurRepository->find($message->getAuthor());
                $message->author_name = $author ? $author->getPrenom() . ' ' . $author->getNom() : 'Auteur inconnu';
            }
            $messages[$course->getCode()] = $courseMessages;
        }

        return $this->render('dashboard.html.twig', [
            'cours' => $cours,
            'messages' => $messages
        ]);
    }
}
