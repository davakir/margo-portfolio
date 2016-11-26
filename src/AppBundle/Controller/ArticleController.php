<?php
/**
 * Created by PhpStorm.
 * User: maxk
 * Date: 15.11.16
 * Time: 21:49
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Article;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends Controller
{
    /**
     * @Route("/admin/articles/")
     */
    public function showAction()
    {
        $articles = $this->getDoctrine()
            ->getRepository('AppBundle:Article')->findAllOrderedByCreateDate();

        return $this->render('admin/articles/table.html.twig',
            array('articles' => $articles));
    }

    /**
     * @Route("/admin/article/{id}/edit", name="edit_form_render")
     */
    public function editFormAction($id)
    {
        $article = $this->getDoctrine()
            ->getRepository('AppBundle:Article')->find($id);

        return $this->render('admin/articles/form.html.twig',
            array('article' => $article));
    }

    /**
     * @Route("/admin/article/create", name="create_form_render")
     */
    public function createFormAction()
    {
        return $this->render('admin/articles/form.html.twig');
    }

    /**
     * @Route("/admin/article/{id}", name="update_article")
     * @Method("POST")
     */
    public function updateAction(Request $request, Article $article)
    {
        $this->setArticleFieldsFromRequest($article, $request);

        $em = $this->getDoctrine()->getManager();
        $em->merge($article);
        $em->flush();

        return $this->redirect('/admin');
    }

    /**
     * @Route("/admin/article/", name="create_article")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        $article = new Article();
        $this->setArticleFieldsFromRequest($article, $request);

        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();

        return $this->redirect('/admin');
    }

    /**
     * @param Article $article
     * @param Request $request
     */
    private function setArticleFieldsFromRequest(Article $article, Request $request)
    {
        $article->setTitle($request->request->get('title'));
        $article->setText($request->request->get('text'));
    }
}