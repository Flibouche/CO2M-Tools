<?php

namespace App\Controller;

use App\Entity\Mail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MailController extends AbstractController
{
    #[Route('/mail', name: 'app_mail')]
    public function index(): Response
    {
        return $this->render('mail/index.html.twig', [
            'controller_name' => 'MailController',
        ]);
    }

    #[Route('/admin/mail/{id}', name: 'admin_mail_message', methods: ['GET'])]
    public function getMailMessage(Mail $mail): JsonResponse
    {
        if (!$mail) {
            return new JsonResponse(['message' => 'Mail not found'], 404);
        }

        return new JsonResponse(['message' => $mail->getMessage()]);
    }
}
