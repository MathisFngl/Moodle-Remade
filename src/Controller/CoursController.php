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
    #[Route('/cours/{code}', name: 'cours_par_code')]
    public function cours(string $code, EntityManagerInterface $em): Response
    {
        $cours = $em->getRepository(Cours::class)->findOneBy(['code' => $code]);

        if (!$cours) {
            throw $this->createNotFoundException('Cours non trouvé');
        }

        return $this->render('cours/cours.html.twig', [
            'cours' => $cours,
            'nav' => 'cours',
        ]);
    }

    #[Route('/cours/{code}/notes', name: 'cours_notes')]
    public function notes(string $code, EntityManagerInterface $em): Response
    {
        $cours = $em->getRepository(Cours::class)->findOneBy(['code' => $code]);

        if (!$cours) {
            throw $this->createNotFoundException('Cours non trouvé');
        }

        return $this->render('cours/notes.html.twig', [
            'cours' => $cours,
            'nav' => 'notes',
        ]);
    }

    #[Route('/cours/{code}/notes/ajouter', name: 'ajouter_note')]
    public function ajouterNote(string $code, EntityManagerInterface $em): Response
    {
        $cours = $em->getRepository(Cours::class)->findOneBy(['code' => $code]);

        if (!$cours) {
            throw $this->createNotFoundException('Cours non trouvé');
        }

        return $this->render('cours/ajouter_note.html.twig', [
            'cours' => $cours,
        ]);
    }

    #[Route('/cours/{code}/participants', name: 'cours_participants')]
    public function participants(string $code, EntityManagerInterface $entityManager): Response
    {
        $cours = $entityManager->getRepository(Cours::class)->findOneBy(['code' => $code]);

        if (!$cours) {
            throw $this->createNotFoundException('Cours non trouvé');
        }

        $participants = $entityManager->getRepository(Participant::class)->findBy(['cours' => $cours]);

        return $this->render('cours/participants.html.twig', [
            'participants' => $participants,
            'cours' => $cours,
            'nav' => 'participants'
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

    #[Route('/cours/{code}/ajouter-participant', name: 'ajouter_participant', methods: ['POST'])]
    public function ajouterParticipant(string $code, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $cours = $entityManager->getRepository(Cours::class)->findOneBy(['code' => $code]);
        if (!$cours) {
            return new JsonResponse(["error" => "Cours introuvable"], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        $userId = $data['id_utilisateur'] ?? null;

        if (!$userId) {
            return new JsonResponse(["error" => "ID utilisateur manquant"], JsonResponse::HTTP_BAD_REQUEST);
        }

        $utilisateur = $entityManager->getRepository(Utilisateur::class)->find($userId);

        if (!$utilisateur) {
            return new JsonResponse(["error" => "Utilisateur introuvable"], JsonResponse::HTTP_NOT_FOUND);
        }

        $existingParticipant = $entityManager->getRepository(Participant::class)
            ->findOneBy(['utilisateur' => $utilisateur, 'cours' => $cours]);

        if ($existingParticipant) {
            return new JsonResponse(["error" => "Utilisateur déjà inscrit"], JsonResponse::HTTP_CONFLICT);
        }

        $participant = new Participant();
        $participant->setUtilisateur($utilisateur);
        $participant->setCours($cours);
        $entityManager->persist($participant);
        $entityManager->flush();

        return new JsonResponse(["success" => "Utilisateur ajouté avec succès"], JsonResponse::HTTP_OK);
    }

    #[Route('/cours/{code}/ajouter-participant-page', name: 'new_participant')]
    public function afficherFormulaireAjout(string $code, EntityManagerInterface $entityManager): Response
    {
        $cours = $entityManager->getRepository(Cours::class)->findOneBy(['code' => $code]);

        if (!$cours) {
            return new Response("Erreur : Aucun cours trouvé", Response::HTTP_NOT_FOUND);
        }

        return $this->render('cours/ajouter_participant.html.twig', [
            'cours' => $cours,
        ]);
    }
}
