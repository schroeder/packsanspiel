<?php

namespace PacksAnSpielBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LoginController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        var_dump($request->get('qr'));
        die();

        return $this->render('PacksAnSpielBundle::default/index.html.twig');
    }
}
