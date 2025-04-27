<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\Message;
use App\Entity\Participant;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_dashboard')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $ues = $entityManager->getRepository(Cours::class)->findAll();
        $users = $entityManager->getRepository(Utilisateur::class)->findAll();

        $userUEs = [];

        foreach ($users as $user) {
            $userId = $user->getId();
            $userUEs[$userId] = [];

            $participants = $entityManager->getRepository(Participant::class)->findBy(['utilisateur' => $user]);

            foreach ($participants as $participant) {
                $cours = $participant->getCours();
                if ($cours !== null) {
                    $userUEs[$userId][] = $cours->getCode();
                }
            }
        }

        $uesData = [];
        foreach ($ues as $ue) {
            $responsableUeId = $ue->getResponsableUe();
            $responsableUe = $entityManager->getRepository(Utilisateur::class)->find($responsableUeId); // Récupération de l'utilisateur responsable

            $responsableNom = $responsableUe ? $responsableUe->getNom() : 'Inconnu';
            $responsablePrenom = $responsableUe ? $responsableUe->getPrenom() : 'Inconnu';

            $uesData[] = [
                'code' => $ue->getCode(),
                'nom' => $ue->getNom(),
                'description' => $ue->getDescription(),
                'image' => $ue->getImage(),
                'responsableUe' => [
                    'id' => $responsableUeId,
                    'nom' => $responsableNom,
                    'prenom' => $responsablePrenom,
                ],
            ];
        }

        return $this->render('admin.html.twig', [
            'ues' => $uesData,
            'users' => $users,
            'userUEs' => $userUEs,
        ]);
    }

    #[Route('/admin/ajouter-ue', name: 'admin_ajouter_ue', methods: ['POST'])]
    public function ajouterUe(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Validation: check for required fields
        if (!isset($data['code']) || !isset($data['nom']) || !isset($data['responsable_ue'])) {
            return new JsonResponse(['success' => false, 'message' => 'Données manquantes (code, nom, ou responsable_ue)']);
        }

        // Create new Cours entity and set its properties
        $cours = new Cours();
        $cours->setCode($data['code']);
        $cours->setNom($data['nom']);
        $cours->setDescription($data['description'] ?? ''); // Default to empty string if description is not provided

        // Handle responsible user
        if (!empty($data['responsable_ue'])) {
            $responsable = $em->getRepository(Utilisateur::class)->find($data['responsable_ue']);
            if ($responsable) {
                $cours->setResponsableUe($responsable->getId());

                // Check if the responsible user is already a participant in this course
                $existing = $em->getRepository(Participant::class)->findOneBy([
                    'utilisateur' => $responsable,
                    'cours' => $cours
                ]);

                if (!$existing) {
                    $participant = new Participant();
                    $participant->setUtilisateur($responsable);
                    $participant->setCours($cours);
                    $em->persist($participant);
                }
            } else {
                return new JsonResponse(['success' => false, 'message' => 'Responsable non trouvé']);
            }
        }

        // Persist the course entity
        $em->persist($cours);
        $em->flush();

        return new JsonResponse(['success' => true, 'message' => 'UE ajoutée']);
    }

    #[Route('/admin/modifier-ue', name: 'admin_modifier_ue', methods: ['POST'])]
    public function modifierUe(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Validation: check if the code is provided
        if (!isset($data['code'])) {
            return new JsonResponse(['success' => false, 'message' => 'Code de l\'UE manquant']);
        }

        $cours = $em->getRepository(Cours::class)->findOneBy(['code' => $data['code']]);

        if (!$cours) {
            return new JsonResponse(['success' => false, 'message' => 'UE non trouvée']);
        }

        // Update course data
        $cours->setNom($data['nom'] ?? $cours->getNom()); // Keep existing name if not provided
        $cours->setDescription($data['description'] ?? $cours->getDescription()); // Keep existing description if not provided
        $cours->setImage($data['image'] ?? $cours->getImage()); // Optional image field

        // Handle responsible user update
        if (!empty($data['responsable_ue'])) {
            $responsable = $em->getRepository(Utilisateur::class)->find($data['responsable_ue']);
            if ($responsable) {
                $cours->setResponsableUe($responsable->getId());

                // Check if the responsible user is already a participant in this course
                $existing = $em->getRepository(Participant::class)->findOneBy([
                    'utilisateur' => $responsable,
                    'cours' => $cours
                ]);

                if (!$existing) {
                    $participant = new Participant();
                    $participant->setUtilisateur($responsable);
                    $participant->setCours($cours);
                    $em->persist($participant);
                }
            } else {
                return new JsonResponse(['success' => false, 'message' => 'Responsable non trouvé']);
            }
        }

        // Persist the changes
        $em->flush();

        return new JsonResponse(['success' => true, 'message' => 'UE mise à jour']);
    }

    #[Route('/admin/supprimer-ue/{code}', name: 'admin_supprimer_ue', methods: ['POST'])]
    public function supprimerUe(string $code, EntityManagerInterface $em): JsonResponse
    {
        $ue = $em->getRepository(Cours::class)->findOneBy(['code' => $code]);
        if (!$ue) {
            return new JsonResponse(['success' => false, 'message' => 'UE non trouvée']);
        }

        // MESSAGE DELETION
        $messageRepo = $em->getRepository(Message::class);
        $messages = $messageRepo->findBy(['cours_code' => $ue->getCode()]);
        foreach ($messages as $message) {
            $em->remove($message);
        }

        // PARTICIPANTS DELETION
        $participantRepo = $em->getRepository(Participant::class);
        $existingParticipants = $participantRepo->findBy(['cours' => $ue]);
        foreach ($existingParticipants as $participant) {
            $em->remove($participant);
        }

        $em->remove($ue);
        $em->flush();

        return new JsonResponse(['success' => true, 'message' => 'UE et ses messages supprimés']);
    }


    #[Route('/admin/ajouter-utilisateur', name: 'admin_ajouter_utilisateur', methods: ['POST'])]
    public function ajouterUtilisateur(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);


        if (!$data || !isset($data['prenom'], $data['nom'], $data['email'], $data['password'], $data['role'])) {
            return new JsonResponse(['success' => false, 'message' => 'Données manquantes'], 400);
        }

        $utilisateur = new Utilisateur();
        $utilisateur->setPrenom($data['prenom']);
        $utilisateur->setNom($data['nom']);
        $utilisateur->setEmail($data['email']);
        $utilisateur->setMotDePasse(password_hash($data['password'], PASSWORD_BCRYPT));

        $roles = [];
        if (in_array($data['role'], ['Etudiant', 'Professeur'])) {
            $roles[] = $data['role'];
        }
        if (!empty($data['isAdmin']) && $data['isAdmin']) {
            $roles[] = 'admin';
        }

        $utilisateur->setRoles($roles); // 🚀 Utiliser setRoles() correctement
        $utilisateur->setAdmin(false); // Toujours false comme demandé

        // 🔹 Ajouter les UEs
        if (!empty($data['ues'])) {
            foreach ($data['ues'] as $ueCode) {
                $cours = $em->getRepository(Cours::class)->findOneBy(['code' => $ueCode]);
                if ($cours) {
                    $participant = new Participant();
                    $participant->setUtilisateur($utilisateur);
                    $participant->setCours($cours);
                    $em->persist($participant);
                }
            }
        }

        $em->persist($utilisateur);
        $em->flush();

        return new JsonResponse(['success' => true, 'message' => 'Utilisateur ajouté']);
    }

    #[Route('/admin/modifier-utilisateur', name: 'admin_modifier_utilisateur', methods: ['POST'])]
    public function modifierUtilisateur(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = $em->getRepository(Utilisateur::class)->find($data['id']);

        if (!$user) {
            return new JsonResponse(['success' => false, 'message' => 'Utilisateur non trouvé']);
        }

        $user->setPrenom($data['prenom']);
        $user->setNom($data['nom']);
        $user->setEmail($data['email']);
        if (!empty($data['password'])) {
            $user->setPassword(password_hash($data['password'], PASSWORD_BCRYPT));
        }
        $user->setRole($data['role']);
        $user->setAdmin($data['isAdmin']);

        $participantRepo = $em->getRepository(Participant::class);
        $existingParticipants = $participantRepo->findBy(['utilisateur' => $user]);
        foreach ($existingParticipants as $participant) {
            $em->remove($participant);
        }

        if (!empty($data['ues'])) {
            foreach ($data['ues'] as $ueCode) {
                $cours = $em->getRepository(Cours::class)->findOneBy(['code' => $ueCode]);
                if ($cours) {
                    $participant = new Participant();
                    $participant->setUtilisateur($user);
                    $participant->setCours($cours);
                    $em->persist($participant);
                }
            }
        }
        $em->flush();
        return new JsonResponse(['success' => true, 'message' => 'Utilisateur mis à jour']);
    }

    #[Route('/admin/supprimer-utilisateur/{id}', name: 'admin_supprimer_utilisateur', methods: ['POST'])]
    public function supprimerUtilisateur(int $id, EntityManagerInterface $em): JsonResponse
    {
        $utilisateur = $em->getRepository(Utilisateur::class)->find($id);

        if (!$utilisateur) {
            return new JsonResponse(['success' => false, 'message' => 'Utilisateur non trouvé']);
        }

        $participantRepo = $em->getRepository(Participant::class);
        $existingParticipants = $participantRepo->findBy(['utilisateur' => $utilisateur]);
        foreach ($existingParticipants as $participant) {
            $em->remove($participant);
        }

        $em->remove($utilisateur);
        $em->flush();

        return new JsonResponse(['success' => true, 'message' => 'Utilisateur supprimé']);
    }

    #[Route('/admin/utilisateur/{id}', name: 'admin_utilisateur_data', methods: ['GET'])]
    public function getUtilisateur(int $id, EntityManagerInterface $em): JsonResponse
    {
        $user = $em->getRepository(Utilisateur::class)->find($id);

        if (!$user) {
            return new JsonResponse(['success' => false, 'message' => 'Utilisateur non trouvé'], 404);
        }

        return new JsonResponse([
            'success' => true,
            'data' => [
                'id' => $user->getId(),
                'prenom' => $user->getPrenom(),
                'nom' => $user->getNom(),
                'email' => $user->getEmail(),
                'role' => $user->getRoleByIndex(0),
                'isAdmin' => $user->isAdmin(),
            ]
        ]);
    }
}
