<?php

namespace App\Controller;

use App\Entity\Cours;
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

        $cours = new Cours();
        $cours->setCode($data['code']);
        $cours->setNom($data['nom']);
        $cours->setDescription($data['description'] ?? '');

        // Traitement du responsable UE
        if (!empty($data['responsable_ue'])) {
            $responsable = $em->getRepository(Utilisateur::class)->find($data['responsable_ue']);
            if ($responsable) {
                $cours->setResponsableUe($responsable->getId());

                // Vérifie si le responsable est déjà inscrit comme participant
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
            }
        }

        $em->persist($cours);
        $em->flush();

        return new JsonResponse(['success' => true, 'message' => 'UE ajoutée']);
    }

    #[Route('/admin/modifier-ue', name: 'admin_modifier_ue', methods: ['POST'])]
    public function modifierUe(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $cours = $em->getRepository(Cours::class)->findOneBy(['code' => $data['code']]);

        if (!$cours) {
            return new JsonResponse(['success' => false, 'message' => 'UE non trouvée']);
        }

        $cours->setCode($data['code']);
        $cours->setNom($data['nom']);
        $cours->setDescription($data['description'] ?? '');
        $cours->setImage($data['image'] ?? null);

        // Traitement du responsable UE
        if (!empty($data['responsable_ue'])) {
            $responsable = $em->getRepository(Utilisateur::class)->find($data['responsable_ue']);
            if ($responsable) {
                $cours->setResponsableUe($responsable->getId());

                // Vérifie si le responsable est déjà inscrit comme participant
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
            }
        }

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

        $participantRepo = $em->getRepository(Participant::class);
        $existingParticipants = $participantRepo->findBy(['cours' => $ue]);
        foreach ($existingParticipants as $participant) {
            $em->remove($participant);
        }

        $em->remove($ue);
        $em->flush();

        return new JsonResponse(['success' => true, 'message' => 'UE supprimée']);
    }

    #[Route('/admin/ajouter-utilisateur', name: 'admin_ajouter_utilisateur', methods: ['POST'])]
    public function ajouterUtilisateur(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $utilisateur = new Utilisateur();
        $utilisateur->setPrenom($data['prenom']);
        $utilisateur->setNom($data['nom']);
        $utilisateur->setEmail($data['email']);
        $utilisateur->setMotDePasse($data['password']);
        $utilisateur->setRole($data['role']);
        $utilisateur->setAdmin($data['isAdmin']);

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
                'role' => $user->getRole(),
                'isAdmin' => $user->isAdmin(),
            ]
        ]);
    }
}
