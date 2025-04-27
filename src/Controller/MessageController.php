<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MessageController
{
    #[Route('/create-message', name: 'create_message', methods: ['POST'])]
    public function createMessage(Request $request, EntityManagerInterface $em)
    {
        $data = json_decode($request->getContent(), true);

        $user = $em->getRepository(Utilisateur::class)->find($data['author']);
        if (!$user) {
            return new JsonResponse(['status' => 'error', 'message' => 'Invalid author ID'], 400);
        }

        $message = new Message();
        $message->setCoursCode($data['coursCode']);
        $message->setTitle($data['title']);
        $message->setContent($data['content']);
        $message->setImportant($data['important']);
        $message->setAuthor($data['author']);

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
        $filename = uniqid() . '.' . $file->guessExtension();
        $filePath = $this->getParameter('kernel.project_dir') . '/public/uploads/files/' . $filename;

        $file->move($this->getParameter('kernel.project_dir') . '/public/uploads/files/', $filename);

        $message = new Message();
        $message->setCoursCode($coursCode)
            ->setTitle($fileTitle)
            ->setContent($fileDescription)
            ->setFile($filename)
            ->setImportant(false)
            ->setAuthor($user->getId());

        $em->persist($message);
        $em->flush();

        return new JsonResponse(['status' => 'success', 'message' => 'File message created successfully'], 200);
    }
}
