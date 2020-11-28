<?php

namespace App\Controller;

use DateTime;
use App\Entity\Article;
use App\Entity\Favoris;
use App\Form\ArticleType;
use App\HelperFunctions\Functions;
use App\Repository\UserRepository;
use Symfony\Component\Finder\Finder;
use App\Repository\ArticleRepository;
use App\Repository\FavorisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="article_accueil")
     */
    public function index(ArticleRepository $ArticleRipo, Functions $func): Response
    {

        return $this->render('article/index.html.twig', [
            'article_accueil' => $article =  $ArticleRipo->findArticleNotDelete(),
            // 'lastArticle' => $ArticleRipo->findLastArticle()[0]
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

    /**
     * @Route("user/article/favoris-{id}" , name="ajout_favoris" )
     */
    public function favoris(FavorisRepository $favorisRepo, Article $article, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();


        if (!$user) {

            return  $this->json([
                'message' => 'unauthorized',
                'user' => $user == null,
                'in_favoris' => false
            ], 403);
        }

        if (!$article->in_favoris($user)) {

            $favoris = new Favoris();
            $favoris->setUser($user);
            $favoris->setArticle($article);
            $favoris->setCreateAt(new DateTime());
            $em->persist($favoris);
            $em->flush();

            return $this->json([

                'code' => 200,
                'message' => "article a été ajouté dans le favoris",
                'in_favoris' => true,

            ], 200);
        } else {

            $favoris = $favorisRepo->findOneBy(['article' => $article, 'user' => $user]);

            $em->remove($favoris);
            $em->flush();
            return $this->json([
                'code' => 200,
                'message' => "article a été  rétiré du  favoris",


            ], 200);
        }
        return $this->json([
            'code' => 200,
            'message' => "article a été ajouté dans le favoris",

        ], 200);
    }

    /**
     * @Route("/user/dashbord/favoris/article" , name="mes_favoris")
     */


    public function favoris_article(UserRepository $userRepo)
    {
        $user = $this->getUser();
        $getFavoris = $userRepo->findOneBy(['email' => $user->getUsername()])->getFavoris();

        return $this->render("user/favoris.html.twig", [
            'user_favoris' => $getFavoris
        ]);
    }
}
