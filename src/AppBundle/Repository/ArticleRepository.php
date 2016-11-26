<?php
/**
 * Created by PhpStorm.
 * User: maxk
 * Date: 15.11.16
 * Time: 21:28
 */

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ArticleRepository extends EntityRepository
{
    public function create(Article $article)
    {
        $this->getEntityManager()->persist($article);
        return $article;
    }

    public function findAllOrderedByCreateDate()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT a FROM AppBundle:Article a ORDER BY a.createDate DESC')
            ->getResult();
    }
}