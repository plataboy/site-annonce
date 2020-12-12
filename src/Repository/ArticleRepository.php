<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;


/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }
    /**
     * @return Article[] Returns an array of Article objects
     */

    public function findArticleNotDelete()
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.user is not null')
            ->orderBy('a.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
    /**
     * @return Article[] Returns an array of Article objects
     */

    public function findLastArticle()
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.category is not null')
            ->orderBy('k.id', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }

    public function find_paginator_article(PaginatorInterface $paginator, Request $request, EntityManagerInterface $em)
    {

        $dql   = "SELECT a FROM App\Entity\Article a WHERE a.user IS NOT NULL ORDER BY a.id DESC";
        $query = $em->createQuery($dql);

        return  $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            16/*limit per page*/
        );
    }




    /**
     * @return User[] Returns an array of User objects
     */

    public function recherche_article(UserInterface $user, $value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.user= :val')
            ->setParameter('val', $user)
            ->andWhere('u.titre LIKE :article')
            ->setParameter('article', '%' . $value . '%')
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult();
    }


    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
