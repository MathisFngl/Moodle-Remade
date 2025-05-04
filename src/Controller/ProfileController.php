<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProfileController extends AbstractController
{
    // Gérer l'affichage du nom et prénom de l'utilisateur et leur changement (ainsi que le changement du mot de passe)
    #[Route('/mon-profil', name: 'profil')]
    public function index(Request $request, UtilisateurRepository $utilisateurRepository, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        /** @var Utilisateur $user */
        $user = $utilisateurRepository->findOneBy([], ['id' => 'ASC']);

        if ($request->isMethod('POST')) {
            $nom = $request->request->get('nouveau_nom');
            $prenom = $request->request->get('nouveau_prenom');
            $motDePasse = $request->request->get('nouveau_mot_de_passe');
            $confirmation = $request->request->get('confirmer_mot_de_passe');

            // Si un nouveau nom ou prénom est fourni, on met à jour
            if ($nom) {
                $user->setNom($nom);
            }

            if ($prenom) {
                $user->setPrenom($prenom);
            }

            // Gestion du mot de passe
            if (!empty($motDePasse) || !empty($confirmation)) {
                if ($motDePasse !== $confirmation) {
                    $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
                    return $this->redirectToRoute('profil');
                }

                if (strlen($motDePasse) < 6) {
                    $this->addFlash('error', 'Le mot de passe doit contenir au moins 6 caractères.');
                    return $this->redirectToRoute('profil');
                }

                $hashedPassword = $passwordHasher->hashPassword($user, $motDePasse);
                $user->setMotDePasse($hashedPassword);
            }

            // Gestion de l'image de profil
            $avatarFile = $request->files->get('avatar');
            if ($avatarFile) {
                // Définir le nom de fichier unique pour éviter les collisions
                $newFilename = uniqid() . '.' . $avatarFile->guessExtension();

                try {
                    // Déplacer le fichier vers le répertoire public
                    $avatarFile->move(
                        $this->getParameter('avatars_directory'), // Dossier dans config/services.yaml
                        $newFilename
                    );
                    // Mettre à jour le chemin du fichier dans l'entité Utilisateur
                    $user->setPhoto($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
                }
            }

            // Persister les modifications
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Informations mises à jour avec succès.');
            return $this->redirectToRoute('profil');
        }

        return $this->render('profil.html.twig', [
            'user' => $user,
        ]);
    }
}
