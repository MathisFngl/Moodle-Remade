<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
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

        return $this->render('admin.html.twig', [
            'ues' => $ues,
            'users' => $users,
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
        $cours->setResponsableUe($data['responsable_ue'] ?? '');

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

        $cours->setNom($data['nom']);
        $cours->setDescription($data['desc'] ?? '');
        $cours->setImage($data['image'] ?? null);
        $cours->setResponsableUe($data['responsable_ue'] ?? '');

        $em->flush();

        return new JsonResponse(['success' => true, 'message' => 'UE mise à jour']);
    }

    #[Route('/admin/supprimer-ue/{code}', name: 'admin_supprimer_ue', methods: ['DELETE'])]
    public function supprimerUe(string $code, EntityManagerInterface $em): JsonResponse
    {
        $ue = $em->getRepository(Cours::class)->findOneBy(['code' => $code]);

        if (!$ue) {
            return new JsonResponse(['success' => false, 'message' => 'UE non trouvée']);
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
        $utilisateur->setMotDePasse($data['password'] ?? '1234');
        $utilisateur->setRole($data['role']);
        $utilisateur->setAdmin($data['isAdmin']);

        // Assignation des UEs (si nécessaire plus tard)
        // foreach ($data['ues'] as $ueCode) {
        //     $ue = $em->getRepository(Cours::class)->findOneBy(['code' => $ueCode]);
        //     if ($ue) {
        //         $utilisateur->addCours($ue);
        //     }
        // }

        $em->persist($utilisateur);
        $em->flush();

        return new JsonResponse(['success' => true, 'message' => 'Utilisateur ajouté']);
    }

    #[Route('/admin/supprimer-utilisateur/{id}', name: 'admin_supprimer_utilisateur', methods: ['DELETE'])]
    public function supprimerUtilisateur(int $id, EntityManagerInterface $em): JsonResponse
    {
        $utilisateur = $em->getRepository(Utilisateur::class)->find($id);

        if (!$utilisateur) {
            return new JsonResponse(['success' => false, 'message' => 'Utilisateur non trouvé']);
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
                // 'ues' => array_map(fn($ue) => $ue->getCode(), $user->getUes()->toArray()), // si relation dispo
            ]
        ]);
    }
}
