<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MessageController extends AbstractController
{
    #[Route('/create-message', name: 'create_message', methods: ['POST'])]
    public function createMessage(Request $request, EntityManagerInterface $em, UserInterface $user): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $query = $em->createQuery(
            'SELECT c FROM App\Entity\Cours c WHERE c.code = :code'
        )->setParameter('code', $data['coursCode']);

        $cours = $query->getOneOrNullResult();

        if (!$cours) {
            return new JsonResponse(['status' => 'error', 'message' => 'Cours introuvable'], 404);
        }

        $message = new Message();
        $message->setCoursCode($data['coursCode']);
        $message->setTitle($data['title']);
        $message->setContent($data['content']);
        $message->setImportant($data['important']);
        $message->setAuthor($user->getId());

        $em->persist($message);
        $em->flush();

        return new JsonResponse(['status' => 'success', 'message' => 'Message created successfully']);
    }

    #[Route('/create-message-file', name: 'create_message_file', methods: ['POST'])]
    public function createMessageFile(Request $request, EntityManagerInterface $em, UserInterface $user): JsonResponse
    {
        $file = $request->files->get('file');
        $fileTitle = $request->get('fileTitle');
        $fileDescription = $request->get('fileDescription');
        $coursCode = $request->get('coursCode');

        if (!$file) {
            return new JsonResponse(['status' => 'error', 'message' => 'No file uploaded'], 400);
        }

        /** @var UploadedFile $file */
        $originalExtension = $file->guessExtension() ?? 'bin';
        $filename = uniqid() . '.' . $originalExtension;

        $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/messages/';
        $file->move($uploadDir, $filename);

        $query = $em->createQuery(
            'SELECT c FROM App\Entity\Cours c WHERE c.code = :code'
        )->setParameter('code', $coursCode);

        $cours = $query->getOneOrNullResult();

        if (!$cours) {
            return new JsonResponse(['status' => 'error', 'message' => 'Cours introuvable'], 404);
        }

        $message = new Message();
        $message->setCoursCode($coursCode)
            ->setTitle($fileTitle)
            ->setContent($fileDescription)
            ->setFile($filename) // Enregistrement du nom du fichier
            ->setImportant(false)
            ->setAuthor($user->getId());

        $em->persist($message);
        $em->flush();

        return new JsonResponse(['status' => 'success', 'message' => 'File message created successfully'], 200);
    }
}
