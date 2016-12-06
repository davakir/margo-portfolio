<?php
/**
 * Created by PhpStorm.
 * User: maxk
 * Date: 15.11.16
 * Time: 21:49
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Article;
use AppBundle\Repository\ArticleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends Controller
{
    /**
     * @Route("/admin/articles")
     */
    public function showAction()
    {
        $articles = $this->_getArticleRep()->findAllOrderedByCreateDate();

        return $this->render('admin/panel-content/articles/table.html.twig',
            array('articles' => $articles));
    }

    /**
     * @Route("/admin/article/{id}/edit", name="edit_form_render")
     */
    public function editFormAction($id)
    {
        $article = $this->getDoctrine()
            ->getRepository('AppBundle:Article')->find($id);

        return $this->render('admin/panel-content/articles/form.html.twig',
            array('article' => $article));
    }

    /**
     * @Route("/admin/article/create", name="create_form_render")
     */
    public function createFormAction()
    {
        return $this->render('admin/panel-content/articles/form.html.twig');
    }

    /**
     * @Route("/admin/article/{id}", name="update_article")
     * @Method("POST")
     *
     * @param $request Request
     * @param $article Article
     * @return RedirectResponse
     */
    public function updateAction(Request $request, Article $article)
    {
        $this->_setArticleFieldsFromRequest($article, $request);
	    
	    $this->_getArticleRep()->update($article);

        return $this->redirect('/admin');
    }

    /**
     * @Route("/admin/article/", name="create_article")
     * @Method("POST")
     * @param Request $request
     * @return RedirectResponse
     */
    public function createAction(Request $request)
    {
        $article = new Article();
        $this->_setArticleFieldsFromRequest($article, $request);

        $this->_getArticleRep()->create($article);

        return $this->redirect('/admin#posts');
    }

    /**
     * @param Article $article
     * @param Request $request
     */
    private function _setArticleFieldsFromRequest(Article $article, Request $request)
    {
        $article->setTitle($request->request->get('title'));
        $article->setText($request->request->get('text'));
    }
	
	/**
	 * @return ArticleRepository
	 */
    private function _getArticleRep()
    {
    	return $this->getDoctrine()
		    ->getRepository('AppBundle:Article');
    }
}