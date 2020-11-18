<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Article;
use App\Repository\UserRepository;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Encoder\EncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Validator\DoctrineInitializer;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/article/user", name="user_article")
     */
    public function index(ArticleRepository $repos): Response
    {



        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/user/registre" ,name="user_registre")
     */
    public function registre(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/registration.html.twig', [

            'form_registration' => $form->createView()

        ]);
    }
    /**
     * @Route("/user/dashbord",name="user_dashbord")
     */

    public function userDashbord(UserRepository $userReposi, UserInterface $user)
    {
        $user_article = $userReposi->findBy(['email' => $user->getUsername()]);

        $user_tab = $user_article[0]->getArticles();


        return $this->render("user/dashbord.html.twig", [
            'user_article' => $user_tab
        ]);
    }
    /**
     * @Route("/user/dashbord/info",name="user_info")
     */

    public function information(UserRepository $userRipo, UserInterface $userInterface, Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $useri = $userRipo->findBy(['email' => $userInterface->getUsername()]);

        $form_info = $this->createForm(UserType::class, $userInterface, [])
            ->add('naissance', DateType::class, ['format' => 'dd-MM-yyyy']);
        dump($useri[0]->getPassword());
        $form_info->handleRequest($request);
        if ($form_info->isSubmitted() && $form_info->isValid()) {
            $useri[0]->setPassword($encoder->encodePassword($userInterface, $userInterface->getPassword()));
            $manager->flush();
        }
        return $this->render('user/information_perso.html.twig', [
            'form_info' => $form_info->createView()
        ]);
    }
}
