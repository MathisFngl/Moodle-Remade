<?php

namespace App\Controller;

use App\Entity\Utilisateur;
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
        $participants = $entityManager->getRepository(Utilisateur::class)->findAll();

        return $this->render('cours/participants.html.twig', [
            'participants' => $participants,
        ]);
    }

    #[Route('/cours/cours/ajouter-participant', name: 'ajouter_participant', methods: ['GET', 'POST'])]
    public function ajouterParticipant(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $etudiantName = $request->get('etudiant');

            if (!empty($etudiantName)) {
                $nomPrenom = explode(' ', trim($etudiantName));
                $prenom = $nomPrenom[0] ?? '';
                $nom = $nomPrenom[1] ?? '';

                // Vérifier si l'étudiant existe déjà
                $existingStudent = $entityManager->getRepository(Utilisateur::class)
                    ->findOneBy(['nom' => $nom, 'prenom' => $prenom]);

                if (!$existingStudent) {
                    $email = strtolower($prenom . '.' . $nom . '@example.com');

                    // Vérifier si l'email existe déjà
                    $existingEmail = $entityManager->getRepository(Utilisateur::class)
                        ->findOneBy(['mail' => $email]);

                    if (!$existingEmail) {
                        $student = new Utilisateur();
                        $student->setNom($nom);
                        $student->setPrenom($prenom);
                        $student->setMail($email);
                        $student->setMotDePasse(password_hash('mdp', PASSWORD_BCRYPT));
                        $student->setRole('etudiant');
                        $student->setAdmin(false);

                        $entityManager->persist($student);
                        $entityManager->flush();

                        $this->addFlash('success', 'Nouvel étudiant ajouté avec succès!');
                    } else {
                        $this->addFlash('warning', 'Cet email est déjà utilisé!');
                    }
                } else {
                    $this->addFlash('warning', 'Cet étudiant existe déjà!');
                }
            } else {
                $this->addFlash('error', 'Veuillez entrer un nom valide!');
            }

            return $this->redirectToRoute('cours_participants');
        }

        return $this->render('cours/ajouter_participant.html.twig');
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
}
