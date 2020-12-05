<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AdminController extends AbstractController
{
    /**
     * @Route("/service/admin/dashboard", name="admin_dashboard")
     */
    public function index(): Response
    {

        return $this->render('admin/index.html.twig', [
            'article_admin' => $this->getDoctrine()->getRepository("App\Entity\Article")->findAll()
        ]);
    }

    /**
     * @Route("/service/admin/dashboard/article/{id}" , name="delete_admin_article", methods="DELETE")
     */
    public function admin_delete_article($id, Article $article, Request $request, EntityManagerInterface $em)
    {
        if ($this->isCsrfTokenValid('admin_delete_article', $request->request->get('token'))) {

            $em->remove($article);
            $em->flush();

            return $this->redirectToRoute("admin_dashboard");
        }
    }
}
