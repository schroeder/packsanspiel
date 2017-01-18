<?php

namespace PacksAnSpielBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LoginController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        $teamId = $request->get('qr');

        if (!$teamId) {
            return $this->render('PacksAnSpielBundle::login/index.html.twig');
        }

        $em = $this->getDoctrine();
        $repo = $em->getRepository("PacksAnSpielBundle:Team");
        $team = $repo->findOneByPasscode($teamId);

        if (!$team) {
            return $this->render('PacksAnSpielBundle::login/index.html.twig');
        } else {
            $token = new UsernamePasswordToken($team, null, "main", $team->getRoles());

            //now the user is logged in
            $this->get("security.token_storage")->setToken($token);

            $event = new InteractiveLoginEvent($request, $token);
            $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);
        }
        return new RedirectResponse($this->generateUrl('packsan'));
    }
}
