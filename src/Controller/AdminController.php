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
        // Récupération de tous les cours
        $query = $entityManager->createQuery(
            'SELECT c FROM App\Entity\Cours c'
        );
        $ues = $query->getResult();

        // Récupération de tous les utilisateurs
        $query = $entityManager->createQuery(
            'SELECT u FROM App\Entity\Utilisateur u'
        );
        $users = $query->getResult();

        $userUEs = [];

        // Associer les utilisateurs aux cours auxquels ils participent
        foreach ($users as $user) {
            $userId = $user->getId();
            $userUEs[$userId] = [];

            $query = $entityManager->createQuery(
                'SELECT c FROM App\Entity\Cours c
                 JOIN App\Entity\Participant p WITH p.cours = c
                 WHERE p.utilisateur = :user'
            )->setParameter('user', $user);

            $participants = $query->getResult();

            foreach ($participants as $participant) {
                $userUEs[$userId][] = $participant->getCode();
            }
        }

        // Récupérer les responsables de chaque UE
        foreach ($ues as &$ue) {
            $query = $entityManager->createQuery(
                'SELECT u FROM App\Entity\Utilisateur u WHERE u.id = :id'
            )->setParameter('id', $ue->getResponsableUe());

            $responsableUe = $query->getOneOrNullResult();

            $ue->responsableUe = [
                'id' => $responsableUe ? $responsableUe->getId() : null,
                'nom' => $responsableUe ? $responsableUe->getNom() : 'Inconnu',
                'prenom' => $responsableUe ? $responsableUe->getPrenom() : 'Inconnu',
            ];
        }

        return $this->render('admin.html.twig', [
            'ues' => $ues,
            'users' => $users,
            'userUEs' => $userUEs,
        ]);
    }

    #[Route('/admin/ajouter-ue', name: 'admin_ajouter_ue', methods: ['POST'])]
    public function ajouterUe(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = $request->request->all();  // Get POST data
        $imageFile = $request->files->get('image'); // Get the image file

        // Validation
        if (!isset($data['code']) || !isset($data['nom']) || !isset($data['responsable_ue'])) {
            return new JsonResponse(['success' => false, 'message' => 'Données manquantes (code, nom, ou responsable_ue)']);
        }

        // Create the course entity
        $cours = new Cours();
        $cours->setCode($data['code']);
        $cours->setNom($data['nom']);
        $cours->setDescription($data['description'] ?? ''); // Default to empty string if description is not provided

        // Handle image upload
        if ($imageFile) {
            $imagePath = $this->uploadImage($imageFile);  // Helper function to handle image upload
            $cours->setImage($imagePath);
        }

        // Handle the responsible user
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

        // Persist the course entity
        $em->persist($cours);
        $em->flush();

        return new JsonResponse(['success' => true, 'message' => 'UE ajoutée']);
    }


    #[Route('/admin/modifier-ue', name: 'admin_modifier_ue', methods: ['POST'])]
    public function modifierUe(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = $request->request->all(); // Get POST data
        $imageFile = $request->files->get('image'); // Get the image file

        // Validation
        if (!isset($data['code'])) {
            return new JsonResponse(['success' => false, 'message' => 'Code de l\'UE manquant']);
        }

        $cours = $em->getRepository(Cours::class)->findOneBy(['code' => $data['code']]);

        if (!$cours) {
            return new JsonResponse(['success' => false, 'message' => 'UE non trouvée']);
        }

        // Update course data
        $cours->setNom($data['nom'] ?? $cours->getNom());
        $cours->setDescription($data['description'] ?? $cours->getDescription());

        // Handle image upload if a new image is provided
        if ($imageFile) {
            $imagePath = $this->uploadImage($imageFile);  // Helper function to handle image upload
            $cours->setImage($imagePath);
        }

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
        $data = $request->request;
        $photoFile = $request->files->get('photo');

        if (!$data->has('prenom') || !$data->has('nom') || !$data->has('email') || !$data->has('password') || !$data->has('roles')) {
            return new JsonResponse(['success' => false, 'message' => 'Données manquantes'], 400);
        }

        $utilisateur = new Utilisateur();
        $utilisateur->setPrenom($data->get('prenom'));
        $utilisateur->setNom($data->get('nom'));
        $utilisateur->setEmail($data->get('email'));
        $utilisateur->setMotDePasse(password_hash($data->get('password'), PASSWORD_BCRYPT));
        $utilisateur->setRoles(json_decode($data->get('roles'), true) ?? []);
        $utilisateur->setAdmin(false);

        if ($photoFile) {
            $photoPath = $this->uploadAvatar($photoFile);
            $utilisateur->setPhoto($photoPath);
        }

        // UEs
        $ues = json_decode($data->get('ues') ?? '[]', true);
        foreach ($ues as $ueCode) {
            $cours = $em->getRepository(Cours::class)->findOneBy(['code' => $ueCode]);
            if ($cours) {
                $participant = new Participant();
                $participant->setUtilisateur($utilisateur);
                $participant->setCours($cours);
                $em->persist($participant);
            }
        }

        $em->persist($utilisateur);
        $em->flush();

        return new JsonResponse(['success' => true, 'message' => 'Utilisateur ajouté']);
    }



    #[Route('/admin/modifier-utilisateur', name: 'admin_modifier_utilisateur', methods: ['POST'])]
    public function modifierUtilisateur(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = $request->request;
        $photoFile = $request->files->get('photo');

        if (!$data->has('id') || !$data->has('prenom') || !$data->has('nom') || !$data->has('email') || !$data->has('roles')) {
            return new JsonResponse(['success' => false, 'message' => 'Données manquantes'], 400);
        }

        $user = $em->getRepository(Utilisateur::class)->find($data->get('id'));
        if (!$user) {
            return new JsonResponse(['success' => false, 'message' => 'Utilisateur non trouvé'], 404);
        }

        $user->setPrenom($data->get('prenom'));
        $user->setNom($data->get('nom'));
        $user->setEmail($data->get('email'));

        if ($data->has('password') && $data->get('password')) {
            $user->setMotDePasse(password_hash($data->get('password'), PASSWORD_BCRYPT));
        }

        $user->setRoles(json_decode($data->get('roles'), true) ?? []);
        $user->setAdmin(false);

        if ($photoFile) {
            $photoPath = $this->uploadAvatar($photoFile);
            $user->setPhoto($photoPath);
        }

        // Participations
        $participantRepo = $em->getRepository(Participant::class);
        foreach ($participantRepo->findBy(['utilisateur' => $user]) as $p) {
            $em->remove($p);
        }

        $ues = json_decode($data->get('ues') ?? '[]', true);
        foreach ($ues as $ueCode) {
            $cours = $em->getRepository(Cours::class)->findOneBy(['code' => $ueCode]);
            if ($cours) {
                $participant = new Participant();
                $participant->setUtilisateur($user);
                $participant->setCours($cours);
                $em->persist($participant);
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
    private function uploadImage($image)
    {
        $uploadDir = $this->getParameter('upload_directory');
        $imageName = uniqid() . '.' . $image->guessExtension();

        $image->move($uploadDir, $imageName);

        return  $imageName;
    }
    private function uploadAvatar($image)
    {
        $uploadDir = $this->getParameter('avatars_directory');
        $imageName = uniqid() . '.' . $image->guessExtension();

        $image->move($uploadDir, $imageName);

        return  $imageName;
    }
}
