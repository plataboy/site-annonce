<?php

namespace App\Controller;


use App\Entity\User;
use App\Entity\ResetPassword;
use Symfony\Component\Mime\Email;
use App\HelperFunctions\Functions;
use App\Repository\UserRepository;

use Symfony\Component\Form\Button;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SendingMail extends AbstractController
{

    /**
     * @Route("/email")
     */

    public function send_mail(MailerInterface $mailer, $addressEmail)
    {
        $key_gener = new Functions();
        $i = 60;
        $key = $key_gener->key_generator($i);

        $emailSender = (new Email())

            ->from('abdoulayld@gmail.com')
            ->to($addressEmail)
            ->subject('Reinitialisez votre mot de passe ! ')
            ->text(' je suis le boss de tout les temps')
            ->html("<p>veuillez click pour reinitailiser votre mot passe </p>
                    <a href=http://localhost:8000/password/reset/key/" . $key . "'>Reinitialise</a>");
        -$mailer->send($emailSender);

        $manager = $this->getDoctrine()->getManager();
        $reset_password = new ResetPassword();
        $users = $this->getDoctrine()->getRepository('App\Entity\User')->findOneBy(['email' => $addressEmail]);
        $reset_password->setUser($users);
        $reset_password->setPassResetKey($key);
        $reset_password->setEmail($addressEmail);
        $manager->persist($reset_password);
        $manager->flush();
    }
}
