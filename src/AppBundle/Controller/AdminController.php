<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
	/**
	 * @Route("/admin", name="admin")
	 */
	public function indexAction(Request $request)
	{
		return $this->render('admin/index.html.twig');
	}
}
