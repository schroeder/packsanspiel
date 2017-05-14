<?php

namespace PacksAnSpielBundle\Controller;

use PacksAnSpielBundle\Entity\TeamLevel;
use PacksAnSpielBundle\Entity\TeamLevelGame;
use PacksAnSpielBundle\Game\GameLogic;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use PacksAnSpielBundle\Repository\GameSubjectRepository;
use PacksAnSpielBundle\Repository\GameRepository;
use PacksAnSpielBundle\Entity\Team;
use PacksAnSpielBundle\Repository\TeamLevelRepository;
use PacksAnSpielBundle\Repository\TeamRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\User;


class PlayGameController extends Controller
{
    /**
     * @Route("/play/selectgame", name="playselectgame")
     */
    public function indexAction(Request $request)
    {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return new RedirectResponse($this->generateUrl('login'));
        }

        $selectedCategory = $request->get('subject');

        /* @var Team $currentTeam */
        $currentTeam = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getEntityManager();

        /* @var GameSubjectRepository $gameSubjectRepository */
        $gameSubjectRepository = $em->getRepository("PacksAnSpielBundle:GameSubject");

        /* @var GameRepository $gameRepository */
        $gameRepository = $em->getRepository("PacksAnSpielBundle:Game");

        /* @var TeamRepository $teamRepository */
        $teamRepository = $em->getRepository("PacksAnSpielBundle:Team");

        /* @var TeamLevelRepository $teamLevelRepository */
        $teamLevelRepository = $em->getRepository("PacksAnSpielBundle:TeamLevel");

        /* @var Team $currentTeam */
        $currentTeam = $teamRepository->find($currentTeam->getId());

        /* @var TeamLevel $currentTeamLevel */
        $currentTeamLevel = $teamLevelRepository->getCurrentTeamLevel($currentTeam, $currentTeam->getCurrentLevel());

        if (!$currentTeamLevel->getTeamLevelGames()) {
        }

        $currentGame = $gameRepository->getCurrentGame($currentTeam);


        $gameSubjectList = [];
        /* @var TeamLevelGame $teamLevelGame */
        foreach ($currentTeamLevel->getTeamLevelGames() as $teamLevelGame) {
            $gameSubject = $teamLevelGame->getAssignedGameSubject();
            $game = false;
            if ($gameSubject->getId() == $selectedCategory) {
                $game = $teamLevelGame->getAssignedGame();
                if ($game && $teamLevelGame->getStartTime() && !$teamLevelGame->getFinishTime()) {
                    return new RedirectResponse($this->generateUrl('packsan'));
                } elseif (!$game) {
                    // select a game
                    $currentGame = $gameRepository->findAFreeGame($currentTeamLevel);
                    $teamLevelGame->setAssignedGame($currentGame);
                    $teamLevelGame->setStartTime(GameLogic::now());
                    $em->persist($teamLevelGame);
                    $em->flush();
                    return $this->render('PacksAnSpielBundle::play/show_game.html.twig',
                        array('team_level' => $teamLevelGame, 'game' => $currentGame));
                } else {
                    return $this->render('PacksAnSpielBundle::play/show_game.html.twig',
                        array('team_level' => $teamLevelGame, 'game' => $game));

                }
            }
        }
    }
}
