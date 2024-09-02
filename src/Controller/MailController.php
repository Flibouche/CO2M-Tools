<?php

namespace App\Controller;

use App\Entity\Mail;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MailController extends AbstractController
{
    #[Route('/admin/mail/{id}', name: 'admin_mail_message', methods: ['GET'])]
    public function getMailMessage(Mail $mail): JsonResponse
    {
        if (!$mail) {
            return new JsonResponse(['message' => 'Mail not found'], 404);
        }

        return new JsonResponse(['message' => $mail->getMessage()]);
    }
}
