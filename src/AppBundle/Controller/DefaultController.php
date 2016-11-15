<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Album;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use AppBundle\Service\Mail\Mail;
use Yandex\Fotki\ImageSizes;

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
		$albums = ($this->get('dao.albums'))->getAlbums(
			($this->get('dao.users'))->getDefaultUser()
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
	 * @Route("/gallery/album/{albumId}")
	 * @param int $albumId
	 * @param Request $request
	 * @return Response
	 */
	public function getAlbumPhotosAction($albumId, Request $request)
	{
		$photos = ($this->get('dao.photos'))->getPhotos($albumId);
		
		return $this->render('default/photos.html.twig', [
			'photos' => $photos,
			'size' => ImageSizes::XXL_SIZE
		]);
	}
	
	/**
	 * @param array $albums
	 * @return array
	 */
	private function __shortenTitle(array $albums)
	{
		$length = 255;
		/**
		 * @var $album Album
		 */
		foreach ($albums as $album)
			$album->setTitle(mb_substr($album->getTitle(), 0, $length, 'UTF-8'));
		
		return $albums;
	}
}
