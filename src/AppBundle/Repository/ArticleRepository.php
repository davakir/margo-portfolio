<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Article;
use Doctrine\Common\CommonException;
use Doctrine\ORM\EntityRepository;

/**
 * Class ArticleRepository
 * @package AppBundle\Repository
 */
class ArticleRepository extends EntityRepository
{
	/**
	 * @param $id
	 * @return null|object
	 * @throws \Exception
	 */
	public function getArticle($id)
	{
		try
		{
			return $this->findOneBy(['id' => $id]);
		}
		catch (CommonException $e)
		{
			throw new \Exception($e);
		}
	}
	
	/**
	 * @param Article $article
	 * @throws \Exception
	 */
	public function create(Article $article)
    {
    	try
	    {
		    $this->getEntityManager()->persist($article);
		    $this->getEntityManager()->flush();
	    }
	    catch (CommonException $e)
	    {
	    	throw new \Exception($e);
	    }
    }
	
	/**
	 * @param Article $article
	 * @throws \Exception
	 */
    public function update(Article $article)
    {
    	try
	    {
		    $this->getEntityManager()->merge($article);
		    $this->getEntityManager()->flush();
	    }
	    catch (CommonException $e)
	    {
		    throw new \Exception($e);
	    }
    }
    
	/**
	 * @return array
	 */
	public function findAllOrderedByCreateDate()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT a FROM AppBundle:Article a ORDER BY a.createDate DESC')
            ->getResult();
    }
	
	/**
	 * @param int $limit
	 * @return array
	 */
    public function findLimitedOrderedByCreateDate($limit = 2)
    {
	    return $this->getEntityManager()
		    ->createQuery('SELECT a FROM AppBundle:Article a ORDER BY a.createDate DESC')
		    ->setMaxResults($limit)
		    ->getResult();
    }
}