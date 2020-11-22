<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Button;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SendingMail extends AbstractController
{

    /**
     * @Route("/email")
     */

    public function send_mail(MailerInterface $mailer, $addressEmail)
    {
        $emailSender = (new Email())
            ->from('abdoulayld@gmail.com')
            ->to($addressEmail)
            ->subject('Reinitialisez votre mot de passe ! ')
            ->text(' je suis le boss de tout les temps')
            ->html("<p>veuillez click pour reinitailiser votre mot passe </p>
                    <a href='http://localhost:8000/pass/forgotten'>Reinitialise</a>");
        -$mailer->send($emailSender);
    }
}
