<?php

namespace App\Controller;

use App\Entity\Cours;
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

        return $this->render('admin.html.twig', [
            'ues' => $ues,
            'users' => $users,
        ]);
    }

    //AJOUT UE
    #[Route('/admin/ajouter-ue', name: 'admin_ajouter_ue', methods: ['POST'])]
    public function ajouterUe(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $cours = new Cours();
        $cours->setCode($data['code']);
        $cours->setNom($data['nom']);
        $cours->setDescription($data['description'] ?? '');
        $cours->setResponsableUe($data['responsable_ue']);

        $em->persist($cours);
        $em->flush();

        return new JsonResponse(['success' => true, 'message' => 'UE ajoutée']);
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

        /*foreach ($data['ues'] as $ueCode) {
            $ue = $em->getRepository(Cours::class)->findOneBy(['code' => $ueCode]);
            if ($ue) {
                $utilisateur->addCours($ue); // ou méthode personnalisée selon ta relation
            }
        }*/

        $em->persist($utilisateur);
        $em->flush();

        return new JsonResponse(['success' => true, 'message' => 'Utilisateur ajouté']);
    }

    #[Route('/admin/user/{id}/data', name: 'admin_user_data', methods: ['GET'])]
    public function getUserData(UserRepository $userRepository, int $id): JsonResponse
    {
        $user = $userRepository->find($id);

        if (!$user) {
            return new JsonResponse(['error' => 'Utilisateur non trouvé'], 404);
        }

        return new JsonResponse([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'ues' => array_map(fn($ue) => $ue->getId(), $user->getUes()->toArray()),
        ]);
    }
}

