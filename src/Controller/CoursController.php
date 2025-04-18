<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Student;

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
    public function participants(EntityManagerInterface $entityManager)
    {
        $participants = $entityManager->getRepository(Student::class)->findAll();

        return $this->render('cours/participants.html.twig', [
            'participants' => $participants,
        ]);
    }

    #[Route('/cours/cours/ajouter-participant', name: 'ajouter_participant', methods: ['GET', 'POST'])]
    public function ajouterParticipant(Request $request, EntityManagerInterface $entityManager)
    {
        if ($request->isMethod('POST')) {
            $etudiantName = $request->get('etudiant');

            if (!empty($etudiantName)) {
                // Vérifie si l'étudiant existe déjà
                $existingStudent = $entityManager->getRepository(Student::class)
                    ->findOneBy(['name' => $etudiantName]);

                if (!$existingStudent) {
                    $student = new Student();
                    $student->setName($etudiantName);

                    $entityManager->persist($student);
                    $entityManager->flush();

                    $this->addFlash('success', 'Nouvel étudiant ajouté avec succès!');
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
        $query = $request->query->get('q', '');

        $students = $entityManager->getRepository(Student::class)->createQueryBuilder('s')
            ->where('s.name LIKE :query')
            ->setParameter('query', "%$query%")
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();

        $data = array_map(fn($student) => ['name' => $student->getName()], $students);

        return $this->json($data);
    }
}
