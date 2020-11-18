<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Article;
use App\Repository\UserRepository;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Validator\DoctrineInitializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Encoder\EncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
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
        foreach ($user_article as $users_article) {
            $user_tab = $users_article->getArticles();
            dump($user_tab);
        }
        return $this->render("user/dashbord.html.twig", [
            'user_article' => $user_tab //$users_article
        ]);
    }
}
