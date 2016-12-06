<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Album;
use AppBundle\Entity\Article;
use AppBundle\Repository\AlbumRepository;
use AppBundle\Repository\ArticleRepository;
use AppBundle\Repository\PhotoRepository;
use AppBundle\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use AppBundle\Service\Mail\Mail;
use Yandex\Fotki\ImageSizes;

/**
 * Class DefaultController
 * @package AppBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="indexpage")
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('default/home.html.twig');
    }
	
	/**
	 * @Route("/gallery", name="gallery")
	 * @return Response
	 */
	public function galleryAction()
	{
		$albums = $this->_getAlbumRep()->getAlbums(
			$this->_getUserRep()->getDefaultUser()->getUserName(), true
		);
		
		return $this->render('default/gallery.html.twig', [
			'albums' => !empty($albums) ? $this->__shortenAlbumTitle($albums) : [],
			'size' => ImageSizes::L_SIZE
		]);
	}
	
	/**
	 * @Route("/about", name="about")
	 * @return Response
	 */
	public function aboutAction()
	{
		$articles = $this->_getArticleRep()->findLimitedOrderedByCreateDate(2);
		
		$articles = $this->__shortenArticleText($articles);
		
		return $this->render('default/about.html.twig', ['articles' => $articles]);
	}
	
	/**
	 * @Route("/about/articles", name="articles")
	 * @return Response
	 */
	public function articlesAction()
	{
		$articles = $this->_getArticleRep()->findAllOrderedByCreateDate();
		$articles = $this->__shortenArticleText($articles, 512);
		
		return $this->render('default/articles.html.twig', ['articles' => $articles]);
	}
	
	/**
	 * @Route("/about/article/{id}", name="show_article")
	 * @param $id integer
	 * @return Response
	 */
	public function showArticleAction($id)
	{
		$article = $this->_getArticleRep()->getArticle($id);
		
		return $this->render('default/article.html.twig', ['article' => $article]);
	}
	
	/**
	 * @Route("/service", name="service")
	 * @return Response
	 */
	public function serviceAction()
	{
		return $this->render('default/service.html.twig');
	}
	
	/**
	 * @Route("/contacts", name="contacts")
	 * @return Response
	 */
	public function contactsAction()
	{
		return $this->render('default/contacts.html.twig');
	}
	
	/**
	 * @Route("/feedback/send", name="feedback")
	 * @param Request $request
	 * @return Response
	 */
	public function sendFeedbackAction(Request $request)
	{
		$encoder = new JsonEncoder();
		
		try {
			$mail = new Mail();
			$mail->addPoint('name', $request->get('name'));
			$mail->addPoint('phone', $request->get('phone'));
			$mail->addPoint('email', $request->get('email'));
			$mail->addPoint('message', $request->get('message'));
			
			$send = $mail->send();
			
			$result = ['success' => true, 'send' => $send];
			
			return new Response($encoder->encode($result, 'json'), 200, array('Content-Type' => 'application/json'));
		}
		catch (\Exception $e) {
			$logger = $this->get('logger');
			$logger->error($e);
			
			$result = ['success' => false, 'data' => $e];
			
			return new Response($encoder->encode($result, 'json'), 200, array('Content-Type' => 'application/json'));
		}
	}
	
	/**
	 * @Route("/gallery/album/{yaAlbumId}")
	 * @param int $yaAlbumId
	 * @return Response
	 */
	public function getAlbumPhotosAction($yaAlbumId)
	{
		$album = $this->_getAlbumRep()->getAlbum($yaAlbumId);
		$photos = $this->_getPhotoRep()->getPhotos($yaAlbumId);
		
		return $this->render('default/photos.html.twig', [
			'album' => $album,
			'photos' => $photos,
			'size' => ImageSizes::XL_SIZE
		]);
	}
	
	/**
	 * @param array $albums
	 * @param $length
	 * @return array
	 */
	private function __shortenAlbumTitle(array $albums, $length = 50)
	{
		/**
		 * @var $album Album
		 */
		foreach ($albums as $album)
			$album->setTitle(mb_substr($album->getTitle(), 0, $length, 'UTF-8'));
		
		return $albums;
	}
	
	/**
	 * @param array $articles
	 * @param int $length
	 * @return array
	 */
	private function __shortenArticleText(array $articles, $length = 255)
	{
		/**
		 * @var $article Article
		 */
		foreach ($articles as $article)
			$article->setText(mb_substr($article->getText(), 0, $length, 'UTF-8'));
		
		return $articles;
	}
	
	/**
	 * @return AlbumRepository
	 */
	private function _getAlbumRep()
	{
		return $this->getDoctrine()->getRepository('AppBundle:Album');
	}
	
	/**
	 * @return PhotoRepository
	 */
	private function _getPhotoRep()
	{
		return $this->getDoctrine()->getRepository('AppBundle:Photo');
	}
	
	/**
	 * @return UserRepository
	 */
	private function _getUserRep()
	{
		return $this->getDoctrine()->getRepository('AppBundle:User');
	}
	
	/**
	 * @return ArticleRepository
	 */
	private function _getArticleRep()
	{
		return $this->getDoctrine()->getRepository('AppBundle:Article');
	}
}
