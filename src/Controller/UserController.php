<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Article;
use App\Form\ArticleType;
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
     * @Route("/user/article/edit-{id}", name="user_article_edit")
     */
    public function edit(UserInterface $user, Article $article,  Request $request, EntityManagerInterface $manager): Response
    {

        if ($user !== $article->getUser()) {
            $this->addFlash('danger', "Article inconnu");
            return $this->redirectToRoute('user_dashbord');
        }
        $form_edit = $this->createform(ArticleType::class, $article);
        //dump($form_edit);exit();
        $form_edit->handleRequest($request);
        if ($form_edit->isSubmitted() && $form_edit->isValid()) {
            $manager->flush();
            $this->addFlash('success', "Votre article a été modifé avec success");
            return $this->redirectToRoute('user_dashbord');
        }
        return $this->render('user/edit.html.twig', [
            'form_article' => $form_edit->createView(),
        ]);
    }

    /**
     * @Route("/article/show-{id}",name="user_article_show")
     */
    public function article_show(UserInterface $user = null, Article $article, ArticleRepository $articleRepo)
    {
        /*if ($user !== $article->getUser()) {
            $this->addFlash('danger', 'cet article n\'est pas disponible');
            return $this->redirectToRoute('user_dashbord');
        }*/
        $user_article_show = $articleRepo->find($article);
        return $this->render('article/article_show.html.twig', [
            'user_article_show' => $user_article_show
        ]);
    }

    /**
     * @Route("/user/article/delete-{id}" , name="article_delete" ,methods="DELETE")
     */
    public function article_delete(UserInterface $userInterface, UserRepository $user, Article $article, EntityManagerInterface $manager, Request $request)
    {

        if ($this->isCsrfTokenValid('article_delete', $request->request->get('token'))) {

            $user->find($userInterface)->removeArticle($article);
            $article->setUserArchiveId($user->find($userInterface)->getId());
            $manager->flush();
            $this->addFlash("danger", "Votre article a été supprimé avec succès");
            return $this->redirectToRoute('user_dashbord');
        }
    }

    /**
     * @Route("/registre" ,name="user_registre" ,methods="POST")
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
