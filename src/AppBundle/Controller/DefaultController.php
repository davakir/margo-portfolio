<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Album;
use AppBundle\Repository\AlbumRepository;
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
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        return $this->render('default/home.html.twig');
    }
	
	/**
	 * @Route("/gallery", name="gallery")
	 * @param Request $request
	 * @return Response
	 */
	public function galleryAction(Request $request)
	{
		$albums = $this->_getAlbumRep()->getAlbums(
			$this->_getUserRep()->getDefaultUser()->getUserName(), true
		);
		
		return $this->render('default/gallery.html.twig', [
			'albums' => !empty($albums) ? $this->__shortenTitle($albums) : [],
			'size' => ImageSizes::L_SIZE
		]);
	}
	
	/**
	 * @Route("/about", name="about")
	 * @param Request $request
	 * @return Response
	 */
	public function aboutAction(Request $request)
	{
		return $this->render('default/about.html.twig');
	}
	
	/**
	 * @Route("/service", name="service")
	 * @param Request $request
	 * @return Response
	 */
	public function serviceAction(Request $request)
	{
		return $this->render('default/service.html.twig');
	}
	
	/**
	 * @Route("/contacts", name="contacts")
	 * @param Request $request
	 * @return Response
	 */
	public function contactsAction(Request $request)
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
	 * @param Request $request
	 * @return Response
	 */
	public function getAlbumPhotosAction($yaAlbumId, Request $request)
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
	 * @return array
	 */
	private function __shortenTitle(array $albums)
	{
		$length = 50;
		/**
		 * @var $album Album
		 */
		foreach ($albums as $album)
			$album->setTitle(mb_substr($album->getTitle(), 0, $length, 'UTF-8'));
		
		return $albums;
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
}
