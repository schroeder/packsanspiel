<?php

namespace PacksAnSpielBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="packsan")
     */
    public function indexAction(Request $request)
    {

        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return new RedirectResponse($this->generateUrl('login'));
        }

        return $this->render('PacksAnSpielBundle::default/index.html.twig');
    }
}
