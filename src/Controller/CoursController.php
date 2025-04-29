<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Utilisateur;
use App\Entity\Participant;
use App\Entity\Cours;
use App\Entity\Examen;
use App\Entity\Note;
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
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $cours = $em->getRepository(Cours::class)->findOneBy(['code' => $code]);

        if (!$cours) {
            $admins = $em->getRepository(Utilisateur::class)->createQueryBuilder('u')
                ->where('u.roles LIKE :role')
                ->setParameter('role', '%ROLE_ADMIN%')
                ->getQuery()
                ->getResult();

            return $this->render('cours/non-autorise.html.twig', [
                'admins' => $admins,
            ]);
        }

        $participant = $em->getRepository(Participant::class)->findOneBy([
            'cours' => $cours,
            'utilisateur' => $user,
        ]);

        if (!$participant) {
            $admins = $em->getRepository(Utilisateur::class)->createQueryBuilder('u')
                ->where('u.roles LIKE :role')
                ->setParameter('role', '%ROLE_ADMIN%')
                ->getQuery()
                ->getResult();

            return $this->render('cours/non-autorise.html.twig', [
                'admins' => $admins,
            ]);
        }

        $messages = $em->getRepository(Message::class)->findBy(
            ['cours_code' => $code],
            ['timestamp' => 'DESC']
        );

        return $this->render('cours/cours.html.twig', [
            'cours' => $cours,
            'messages' => $messages,
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

        $examens = $em->getRepository(Examen::class)->findBy(['cours' => $cours]);
        $examStats = [];

        foreach ($examens as $examen) {
            $notes = $em->getRepository(Note::class)->findBy(['examen' => $examen]);

            if (!empty($notes)) {
                $values = array_map(fn($note) => $note->getNote(), $notes);
                $examStats[$examen->getId()] = [
                    'id' => $examen->getId(),
                    'nom' => $examen->getTitre(),
                    'bareme' => $examen->getBareme(),
                    'moyenne' => array_sum($values) / count($values),
                    'min' => min($values),
                    'max' => max($values)
                ];
            } else {
                $examStats[$examen->getId()] = [
                    'nom' => $examen->getTitre(),
                    'bareme' => $examen->getBareme(),
                    'moyenne' => 'N/A',
                    'min' => 'N/A',
                    'max' => 'N/A'
                ];
            }
        }

        return $this->render('cours/notes.html.twig', [
            'cours' => $cours,
            'examStats' => $examStats,
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

        $participants = $em->getRepository(Participant::class)->findBy(['cours' => $cours]);

        return $this->render('cours/ajouter_note.html.twig', [
            'cours' => $cours,
            'participants' => $participants,
        ]);
    }
    #[Route('/cours/{code}/notes/enregistrer', name: 'enregistrer_notes', methods: ['POST'])]
    public function enregistrerNotes(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['idExamen']) || !isset($data['notes'])) {
            return new JsonResponse(['error' => 'Données manquantes'], 400);
        }

        $examen = $em->getRepository(Examen::class)->find($data['idExamen']);
        if (!$examen) {
            return new JsonResponse(['error' => 'Examen introuvable'], 404);
        }

        foreach ($data['notes'] as $noteData) {
            $utilisateur = $em->getRepository(Utilisateur::class)->find($noteData['idUtilisateur']);
            if (!$utilisateur) continue;

            $note = new Note();
            $note->setExamen($examen);
            $note->setUtilisateur($utilisateur);
            $note->setNote((float) $noteData['note']);

            $em->persist($note);
        }

        $em->flush();

        return new JsonResponse(['success' => true, 'message' => 'Toutes les notes ont été enregistrées.']);
    }
    #[Route('/examen/creer', name: 'creer_examen', methods: ['POST'])]
    public function creerExamen(Request $request, EntityManagerInterface $em): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (!isset($data['nom'], $data['bareme'], $data['codeCours'])) {
                return new JsonResponse(['error' => 'Données incomplètes'], 400);
            }

            $cours = $em->getRepository(Cours::class)->findOneBy(['code' => $data['codeCours']]);

            if (!$cours) {
                return new JsonResponse(['error' => 'Cours introuvable'], 404);
            }

            $examen = new Examen();
            $examen->setTitre($data['nom']);
            $examen->setBareme((float) $data['bareme']);
            $examen->setCours($cours); // Assure-toi que cette méthode existe

            $em->persist($examen);
            $em->flush();

            return new JsonResponse(['idExamen' => $examen->getId()], 201);

        } catch (\Throwable $e) {
            return new JsonResponse(['error' => 'Erreur interne : ' . $e->getMessage()], 500);
        }
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





    #[Route('/delete-message/{id}', name: 'delete_message', methods: ['DELETE'])]
    public function deleteMessage(int $id, EntityManagerInterface $em): JsonResponse
    {
        $message = $em->getRepository(Message::class)->find($id);

        if (!$message) {
            return new JsonResponse(['status' => 'error', 'message' => 'Message not found'], 404);
        }

        $em->remove($message);
        $em->flush();

        return new JsonResponse(['status' => 'success', 'message' => 'Message deleted successfully']);
    }

    #[Route('/update-message/{id}', name: 'update_message', methods: ['PUT'])]
    public function updateMessage(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $message = $em->getRepository(Message::class)->find($id);

        if (!$message) {
            return new JsonResponse(['status' => 'error', 'message' => 'Message not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['title'])) {
            $message->setTitle($data['title']);
        }
        if (isset($data['content'])) {
            $message->setContent($data['content']);
        }

        $em->flush();

        return new JsonResponse(['status' => 'success', 'message' => 'Message updated successfully']);
    }
    #[Route('/examen/{id}/supprimer', name: 'supprimer_examen', methods: ['DELETE'])]
    public function supprimerExamen(int $id, EntityManagerInterface $em): JsonResponse
    {
        $examen = $em->getRepository(Examen::class)->find($id);

        if (!$examen) {
            return new JsonResponse(['error' => 'Examen introuvable'], 404);
        }

        // Supprimer toutes les notes liées
        $notes = $em->getRepository(Note::class)->findBy(['examen' => $examen]);

        foreach ($notes as $note) {
            $em->remove($note);
        }

        // Supprimer l'examen
        $em->remove($examen);
        $em->flush();

        return new JsonResponse(['success' => 'Examen supprimé avec succès']);
    }
    #[Route('/examen/{id}/modifier', name: 'modifier_page_examen', methods: ['GET'])]
    public function modifierPageExamen(int $id, EntityManagerInterface $em): Response
    {
        $examen = $em->getRepository(Examen::class)->find($id);

        if (!$examen) {
            throw $this->createNotFoundException('Examen introuvable');
        }

        $cours = $examen->getCours(); // Assure-toi que Examen::getCours() existe

        $notes = $em->getRepository(Note::class)->findBy(['examen' => $examen]);

        return $this->render('cours/modifier_notes.html.twig', [
            'cours' => $cours,
            'examen' => $examen,
            'notes' => $notes
        ]);
    }
    #[Route('/examen/{id}/update-notes', name: 'update_notes_examen', methods: ['PUT'])]
    public function updateNotes(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $examen = $em->getRepository(Examen::class)->find($id);

        if (!$examen) {
            return new JsonResponse(['error' => 'Examen non trouvé'], 404);
        }

        foreach ($data['notes'] as $noteData) {
            $note = $em->getRepository(Note::class)->find($noteData['idNote']);
            if ($note && $note->getExamen()->getId() === $id) {
                $note->setNote((float)$noteData['nouvelleNote']);
                $em->persist($note);
            }
        }

        $em->flush();

        return new JsonResponse(['success' => 'Notes mises à jour avec succès.']);
    }


}