<?php

namespace PacksAnSpielBundle\Controller;

use PacksAnSpielBundle\Entity\Actionlog;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use PacksAnSpielBundle\Game\GameActionLogger;
use PacksAnSpielBundle\Entity\Team;
use PacksAnSpielBundle\Repository\TeamRepository;

class LoginController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        /* @var GameActionLogger $logger */
        $logger = $this->get('packsan.action.logger');

        $teamId = $request->get('qr');

        if (!$teamId) {
            return $this->render('PacksAnSpielBundle::login/index.html.twig');
        }

        $em = $this->getDoctrine();
        /* @var TeamRepository $repo */
        $repo = $em->getRepository("PacksAnSpielBundle:Team");
        /* @var Team $team */
        $team = $repo->findOneByPasscode($teamId);

        if (!$team) {
            $logger->logAction("Failed login try with code $teamId", Actionlog::LOGLEVEL_WARN);
            return $this->render('PacksAnSpielBundle::login/index.html.twig');
        } else {
            $token = new UsernamePasswordToken($team, null, "main", $team->getRoles());

            //now the user is logged in
            $this->get("security.token_storage")->setToken($token);

            $event = new InteractiveLoginEvent($request, $token);
            $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);
            $logger->logAction("Team logged in.", Actionlog::LOGLEVEL_INFO, $team);
        }
        return new RedirectResponse($this->generateUrl('packsan'));
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction(Request $request)
    {
        $this->get('security.token_storage')->setToken(null);
        $this->get('request')->getSession()->invalidate();
        return new RedirectResponse($this->generateUrl('login'));
    }
}
