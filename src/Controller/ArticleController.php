<?php

namespace App\Controller;

use DateTime;
use App\Entity\Article;
use App\Entity\Favoris;
use App\Form\ArticleType;
use App\Repository\UserRepository;
use App\Repository\VilleRepository;
use App\Repository\RegionRepository;
use App\Repository\ArticleRepository;
use App\Repository\FavorisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
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
    public function index(PaginatorInterface $paginator, Request $request,  ArticleRepository $articleRipo, EntityManagerInterface $em): Response
    {
        $paginator_article = $articleRipo->find_paginator_article($paginator, $request, $em);
        $category = $this->getDoctrine()->getRepository("App\Entity\Category")->findAll();
        $region = $this->getDoctrine()->getRepository("App\Entity\Region")->findAll();



        return $this->render('article/index.html.twig', [
            'article_accueil' => $paginator_article,
            'lastArticle' => $articleRipo->findArticleNotDelete(),
            'category' => $category,
            'region' => $region

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
            //   if($form->getData())
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

    /**
     * @Route("/article", name="recherche_article")
     */

    public function recherche(VilleRepository $villeRepository, Request $request)
    {

        // if ($this->isCsrfTokenValid('search_article', $request->request->get('token'))) {

        $article_recherche = $villeRepository->zone_de_recherhe($_REQUEST["ville_input"], $_REQUEST["codeDepartement"]);
        foreach ($article_recherche as $dataTesult) {
            $ville[] = '<ul><li>' . $dataTesult->getName() . '(' . $dataTesult->getCodeVille() . ')</li></ul>';
            $departement[] = $dataTesult->getCodeDepartement();
        }

        return $this->json([
            'ville' => $ville,
            'code_departement' =>  $departement,
            //'post' => $_REQUEST["ville_input"] ?? 0,

        ], 200);



        // return $this->render("article/index.html.twig", [
        //     //   'user_article' => $article_recherche ?? null
        // ]);
    }


    //region departement ville recherche_article
    /**
     * @Route("/departement",name="accueil_departement")
     */

    public function getviille(RegionRepository $repoRepo)
    {

        $departements =   $this->getDoctrine()->getRepository("App\Entity\Departement")->findAll();

        foreach ($departements  as $dataDepartement) {
            $departement[] = $dataDepartement;
        }



        return $this->json([
            'departement' => $departement,
        ], 200);
    }
}
