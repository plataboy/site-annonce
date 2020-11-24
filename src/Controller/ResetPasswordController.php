<?php

namespace App\Controller;

use App\Entity\ResetPassword;
use App\Controller\SendingMail;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ResetPasswordRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Validator\DoctrineInitializer;
use App\HelperFunctions\Functions;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ResetPasswordController extends AbstractController
{


    /**
     * @Route("/pass/forgotten" , name="pass_oublie")
     */
    public function pass_oublie(MailerInterface $mailer, SendingMail $mail, UserInterface $userInterface = null,  Request $request, UserRepository $userRepo)
    {
        if ($userInterface) {

            return $this->redirectToRoute('user_dashbord');
        }

        $checkEmail = $request->request->get('email');

        if ($this->isCsrfTokenValid('mot_pass_oublie', $request->request->get('token'))) {

            if ($userRepo->findOneBy(['email' => $checkEmail])) {

                $mail->send_mail($mailer, $checkEmail);

                $this->addFlash("success", "Votre message de reinitialisation envoyé  , Veuillez vérifiez votre compte email !");
            } else {

                $this->addFlash("danger", "Cet Email n'existe pas !");
            }
        }

        return $this->render("user/mot_pass_oublie.html.twig", []);
    }

    /**
     * @Route("/password/reset/key/{key}",name="password_reset" )
     */
    public function pass_reset(UserRepository $user, Request $request, $key, ResetPasswordRepository $passwordRepo, EntityManagerInterface $em)
    {
        $key_verify_url = $request->attributes->get('key');
        $password_field = $request->request->get('password');
        $password_confirm_field = $request->request->get('password-confirm');
        $csrf_token = $request->request->get('token');
        $key_verify_db = $passwordRepo->findOneBy(['passResetKey' => $key_verify_url]);

        dump($key_verify_db);


        if ($request->isMethod('POST') && $this->isCsrfTokenValid('mot_passe_reset', $csrf_token)) {



            if ($key_verify_url === $key_verify_db->getPassResetKey() && $password_field == $password_confirm_field) {


                $key_verify_db->setPassResetVerify($key_verify_db->getPassResetKey());

                $key_verify_db->setPassResetKey(NULL);

                //modication du motpass
                //  $user_modication_password = $user->findBy(['user' => $key_verify_db->getUser()]);

                // dump($user_modication_password);
                $em->flush();
            } else {

                $this->addFlash('danger', 'Votre code de reinitialisation a expirer  ou mot passe non identique !');
            }
        }


        return $this->render('user/password_reset.html.twig', ['key_id' => $key]);
    }
}
