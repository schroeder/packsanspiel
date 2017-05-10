<?php

namespace PacksAnSpielBundle\Controller;

use PacksAnSpielBundle\Entity\GameSubject;
use PacksAnSpielBundle\Entity\TeamLevelGame;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use PacksAnSpielBundle\Repository\GameSubjectRepository;
use PacksAnSpielBundle\Entity\Team;
use PacksAnSpielBundle\Repository\TeamLevelRepository;
use PacksAnSpielBundle\Repository\GameRepository;
use Symfony\Component\HttpFoundation\Session\Session;

class GameAdminController extends Controller
{
    /**
     * @Route("/gameadmin", name="gameadmin")
     */
    public function indexAction(Request $request)
    {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_GAME')) {
            return new RedirectResponse($this->generateUrl('login'));
        }

        $em = $this->getDoctrine();

        /* @var Session $session */
        $session = $request->getSession();
        $gameId = $session->get('game_id');
        $gamePasscode = $session->get('game_passcode');

        /* @var GameRepository $gameRepo */
        $gameRepo = $em->getRepository("PacksAnSpielBundle:Game");

        /* @var Game $game */
        $game = $gameRepo->findGameByPasscode($gamePasscode);

        return $this->render('PacksAnSpielBundle::gameadmin/index.html.twig',
            array('error_message' => '', 'game' => $game));
    }
}
