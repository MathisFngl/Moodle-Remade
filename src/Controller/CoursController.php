<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoursController extends AbstractController
{
    #[Route('/cours/cours', name: 'cours_cours')]
    public function we4e(): Response
    {
        return $this->render('cours/cours_cours.html.twig');
    }
    #[Route('/cours/cours/notes', name: 'cours_notes')]
    public function notes(): Response
    {
        return $this->render('cours/notes.html.twig');
    }
    #[Route('/cours/cours/notes/ajouter', name: 'ajouter_note')]
    public function ajouterNote(): Response
    {
        return $this->render('cours/ajouter_note.html.twig');
    }

    #[Route('/cours/cours/participants', name: 'cours_participants')]
    public function participants()
    {
        $participants = [
            ['name' => 'Esteban Gomez', 'role' => 'Etudiant', 'group' => 'Groupe A'],
            ['name' => 'John Doe', 'role' => 'Professeur', 'group' => 'Groupe B'],
        ];

        return $this->render('cours/participants.html.twig', [
            'participants' => $participants,
        ]);
    }

    #[Route('/cours/cours/ajouter-participant', name: 'ajouter_participant', methods: ['GET', 'POST'])]
    public function ajouterParticipant(Request $request)
    {
        if ($request->isMethod('POST')) {
            $etudiant = $request->get('etudiant');

            $this->addFlash('success', 'New participant added successfully!');
            return $this->redirectToRoute('cours_participants');
        }

        return $this->render('cours/ajouter_participant.html.twig');
    }
}