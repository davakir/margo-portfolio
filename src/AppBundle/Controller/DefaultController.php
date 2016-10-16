<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Service\Mail\Mail;

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
	 * @Route("/home", name="homepage")
	 * @param Request $request
	 * @return Response
	 */
	public function homeAction(Request $request)
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
		return $this->render('default/gallery.html.twig');
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
	 */
	public function feedbackSend(Request $request)
	{
		$data = $request->get('data');
		
		
		try {
			$mail = new Mail();
			
			$mail->addPoint("name", $data["name"]);
			$mail->addPoint("phone", $data["phone"]);
			$mail->addPoint("email", $data["email"]);
			$mail->addPoint("message", $data["message"]);
			
			$send = $mail->send();
			echo json_encode(array("status" => "OK", "send" => $send));
		}
		catch (\Exception $e) {
			echo json_encode(array("status" => "Error"));
		}
	}
}
