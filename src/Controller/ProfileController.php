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

class ProfileController extends AbstractController
{
    #[Route('/mon-profil', name: 'profil')]
    public function index(Request $request, UtilisateurRepository $utilisateurRepository, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        /** @var Utilisateur $user
        $user = $this->getUser();*/

        //TAMPP
        $user = $utilisateurRepository->findOneBy([], ['id' => 'ASC']);

        if ($request->isMethod('POST')) {
            $nom = $request->request->get('nouveau_nom');
            $prenom = $request->request->get('nouveau_prenom');
            $motDePasse = $request->request->get('nouveau_mot_de_passe');
            $confirmation = $request->request->get('confirmer_mot_de_passe');

            if ($nom) {
                $user->setNom($nom);
            }

            if ($prenom) {
                $user->setPrenom($prenom);
            }

            // Si un des deux champs mot de passe est rempli, on traite la modification
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
