<?php

namespace App\Controller;

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatController extends AbstractController
{
    #[Route('/chat-fragment', name: 'chat_fragment')]
    public function chatFragment(): Response
    {
        return $this->render('chat.html.twig');
    }
}