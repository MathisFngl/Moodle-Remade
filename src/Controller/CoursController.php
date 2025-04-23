<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Entity\Participant;
use App\Entity\Cours;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class CoursController extends AbstractController
{
    #[Route('/cours/cours', name: 'cours_cours')]
    public function we4e(): Response
    {
        return $this->render('cours/cours.html.twig');
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
    public function participants(EntityManagerInterface $entityManager): Response
    {
        $cours = $entityManager->getRepository(Cours::class)->findOneBy([]); // Vérifie ici si un cours existe
        $participants = $entityManager->getRepository(Participant::class)->findAll();

        return $this->render('cours/participants.html.twig', [
            'participants' => $participants,
            'cours' => $cours, // ✅ Ajoute la variable cours ici !
        ]);
    }





    #[Route('/search_students', name: 'search_students', methods: ['GET'])]
    public function searchStudents(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $query = $request->query->get('q');

        if (!$query) {
            return new JsonResponse([], JsonResponse::HTTP_OK);
        }

        try {
            $students = $entityManager->getRepository(Utilisateur::class)
                ->createQueryBuilder('u')
                ->where('u.nom LIKE :query OR u.prenom LIKE :query')
                ->setParameter('query', '%' . $query . '%')
                ->setMaxResults(10)
                ->getQuery()
                ->getResult();

            return new JsonResponse(array_map(fn($s) => [
                'id' => $s->getId(),
                'name' => $s->getPrenom() . ' ' . $s->getNom(),
            ], $students), JsonResponse::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/cours/cours/ajouter-participant', name: 'ajouter_participant', methods: ['POST'])]
    public function ajouterParticipant(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $userId = $data['id_utilisateur'] ?? null;
        $coursId = $data['id_cours'] ?? null; // Vérifie que cet ID est bien récupéré

        if (!$userId || !$coursId) {
            return new JsonResponse(["error" => "Données invalides"], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Vérifier que l'utilisateur et le cours existent
        $utilisateur = $entityManager->getRepository(Utilisateur::class)->find($userId);
        $cours = $entityManager->getRepository(Cours::class)->find($coursId);

        if (!$utilisateur || !$cours) {
            return new JsonResponse(["error" => "Utilisateur ou cours introuvable"], JsonResponse::HTTP_NOT_FOUND);
        }

        // Vérifier si l'utilisateur est déjà inscrit à ce cours
        $existingParticipant = $entityManager->getRepository(Participant::class)
            ->findOneBy(['utilisateur' => $utilisateur, 'cours' => $cours]);

        if ($existingParticipant) {
            return new JsonResponse(["error" => "Utilisateur déjà inscrit"], JsonResponse::HTTP_CONFLICT);
        }

        // Ajouter l'utilisateur comme participant
        $participant = new Participant();
        $participant->setUtilisateur($utilisateur);
        $participant->setCours($cours);
        $entityManager->persist($participant);
        $entityManager->flush();

        return new JsonResponse(["success" => "Utilisateur ajouté avec succès"], JsonResponse::HTTP_OK);
    }
    #[Route('/cours/cours/ajouter-participant-page', name: 'new_participant')]
    public function afficherFormulaireAjout(EntityManagerInterface $entityManager): Response
    {
        // 🔍 Récupérer un cours existant (ajuste selon ta logique)
        $cours = $entityManager->getRepository(Cours::class)->findOneBy([]);

        if (!$cours) {
            return new Response("Erreur : Aucun cours trouvé", Response::HTTP_NOT_FOUND);
        }

        return $this->render('cours/ajouter_participant.html.twig', [
            'cours' => $cours, // ✅ Envoie la variable "cours" à Twig !
        ]);
    }




}