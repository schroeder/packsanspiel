<?php

namespace PacksAnSpielBundle\Controller;

use PacksAnSpielBundle\Entity\TeamLevel;
use PacksAnSpielBundle\Entity\TeamLevelGame;
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
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use AppBundle\Entity\User;


class PlayGameController extends FOSRestController
{
    /**
     * @Rest\Post("/play/selectGame")
     */
    public function indexAction(Request $request)
    {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return new RedirectResponse($this->generateUrl('login'));
        }

        $selectedCategory = $request->get('gameSubject');

        /* @var Team $currentTeam */
        $currentTeam = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine();

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


        $currentGame = $gameRepository->findAFreeGame($currentTeamLevel);
        die();
        $gameSubjectList = [];
        /* @var TeamLevelGame $teamLevelGame */
        foreach ($currentTeamLevel->getTeamLevelGames() as $teamLevelGame) {
            $gameSubject = $teamLevelGame->getAssignedGameSubject();
            $game = false;
            if ($gameSubject->getId() == $selectedCategory) {
                $game = $teamLevelGame->getAssignedGame();
                if ($game && $teamLevelGame->getStartTime() && !$teamLevelGame->getFinishTime()) {
// alraedy a selected game
                    $currentGame = $gameRepository->getCurrentGame($currentTeam);
                } else {
// select a game
                    $currentGame = $gameRepository->findAFreeGame($currentTeamLevel);

                }

            }
            $gameSubjectList[] = $gameSubject;
        }
        $result = array(
            'subject_list' => $gameSubjectList,
            'team' => $currentTeam,
            'current_game' => $currentGame
        );
        if ($result === null) {
            return new View("there are no users exist", Response::HTTP_NOT_FOUND);
        }
        return $result;
    }
}
