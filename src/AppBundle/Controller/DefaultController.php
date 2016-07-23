<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('default/index.html.twig');
    }
	
	/**
	 * @Route("/gallery", name="gallery")
	 */
	public function galleryAction(Request $request)
	{
		return $this->render('default/gallery.html.twig');
	}
	
	/**
	 * @Route("/about", name="about")
	 */
	public function aboutAction(Request $request)
	{
		return $this->render('default/about.html.twig');
	}
	
	/**
	 * @Route("/service", name="service")
	 */
	public function serviceAction(Request $request)
	{
		return $this->render('default/service.html.twig');
	}
	
	/**
	 * @Route("/contacts", name="contacts")
	 */
	public function contactsAction(Request $request)
	{
		return $this->render('default/contacts.html.twig');
	}
}
