<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="article_accueil")
     */
    public function index(ArticleRepository $ArticleRipo): Response
    {
        return $this->render('article/index.html.twig', [
            'article_accueil' => $article =  $ArticleRipo->findArticleNotDelete()
        ]);
    }
    /**
     * @Route("/user/article/add",name="add_article")
     */
    public function add(Request $request, EntityManagerInterface $manager, UserInterface $user = null)
    {
        if (!$user) {

            return $this->redirectToRoute('app_login');
        }
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article->setUser($user);
            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute("article_accueil");
            exit();
        }

        return $this->render('article/add.html.twig', [
            'form_article' => $form->createView()
        ]);
    }
}
