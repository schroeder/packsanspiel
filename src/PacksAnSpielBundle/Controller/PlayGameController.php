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
use PacksAnSpielBundle\Repository\MemberRepository;
use PacksAnSpielBundle\Entity\Member;


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
                $gameSubjectInfoList = $currentTeamLevel->getTeamLevelInfo();

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
                        array('team_level' => $teamLevelGame, 'game' => $currentGame, 'team' => $currentTeam, 'level_info' => $gameSubjectInfoList));
                } else {
                    return $this->render('PacksAnSpielBundle::play/show_game.html.twig',
                        array('team_level' => $teamLevelGame, 'game' => $game, 'team' => $currentTeam, 'level_info' => $gameSubjectInfoList));
                }
            }
        }
    }

    /**
     * @Route("/play/jumplevel", name="playjumplevel")
     */
    public function jumpLevelAction(Request $request)
    {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return new RedirectResponse($this->generateUrl('login'));
        }
        $em = $this->getDoctrine()->getEntityManager();

        /* @var Team $currentTeam */
        $currentTeam = $this->get('security.token_storage')->getToken()->getUser();

        /* @var TeamRepository $teamRepository */
        $teamRepository = $em->getRepository("PacksAnSpielBundle:Team");

        /* @var TeamLevelRepository $teamLevelRepository */
        $teamLevelRepository = $em->getRepository("PacksAnSpielBundle:TeamLevel");

        /* @var Team $currentTeam */
        $currentTeam = $teamRepository->find($currentTeam->getId());

        /* @var TeamLevel $currentTeamLevel */
        $currentTeamLevel = $teamLevelRepository->getCurrentTeamLevel($currentTeam, $currentTeam->getCurrentLevel());

        $gameSubjectInfoList = $currentTeamLevel->getTeamLevelInfo();
        $errorMessage = false;
        $levelJumpTeam = false;

        if ($gameSubjectInfoList['count_games_won'] < 2) {
            $errorMessage = "Ihr könnt noch kein Level aufsteigen!";
        } else {
            $jumpLevelTeamPasscode = $request->get('qr');

            if ($jumpLevelTeamPasscode) {
                /* @var MemberRepository $repo */
                $repo = $em->getRepository("PacksAnSpielBundle:Member");
                /* @var Member $member */
                $member = $repo->findOneByPasscode($jumpLevelTeamPasscode);

                /* @var Team $team */
                $levelJumpTeam = $member->getTeam();
            }

        }

        /* @var TeamLevel $currentTeamLevel */
        $levelJumpTeamLevel = $teamLevelRepository->getCurrentTeamLevel($currentTeam, $currentTeam->getCurrentLevel());
        $gameSubjectInfoList = $levelJumpTeamLevel->getTeamLevelInfo();

        if ($gameSubjectInfoList['count_games_won'] < 2) {
            $errorMessage = "Ihr könnt noch kein Level aufsteigen, ihr habt noch keine zwei Spiele gewonnen!";
        } elseif ($levelJumpTeam && !$errorMessage) {
            if ($levelJumpTeam->getId() == $currentTeam->getId()) {
                $errorMessage = "Ihr müsst euch eine andere Gruppe suchen!";
            } elseif ($levelJumpTeam->getCurrentLevel() != $currentTeam->getCurrentLevel()) {
                $errorMessage = "Ihr müsst euch eine Gruppe aus Level " . $currentTeam->getCurrentLevel() . " suchen!";

            } elseif ($levelJumpTeam->getGrade() != $currentTeam->getGrade()) {
                $errorMessage = "Ihr müsst euch eine Gruppe aus eurer Stufe suchen!";

            } else { /* Gruppen zusammenführen und zum Spielfeld*/

                $currentTeamLevel->setFinishTime(GameLogic::now());
                $em->persist($currentTeamLevel);

                $levelJumpTeamLevel = $teamLevelRepository->getCurrentTeamLevel($currentTeam, $currentTeam->getCurrentLevel());
                $levelJumpTeamLevel->setFinishTime(GameLogic::now());
                $em->persist($currentTeamLevel);

                $newLevelNumber = $currentTeam->getCurrentLevel()->getNumber() + 1;

                if ($newLevelNumber == 8) {
                    return new RedirectResponse($this->generateUrl('playgamefinished'));
                }

                $levelRepository = $em->getRepository("PacksAnSpielBundle:Level");
                $newLevel = $levelRepository->findOneByNumber($newLevelNumber);

                $levelJumpTeam->setParentTeam($currentTeam);
                $currentTeam->setParentTeam($currentTeam);
                $currentTeam->setCurrentLevel($newLevel);
                $levelJumpTeam->setCurrentLevel($newLevel);
                $em->persist($currentTeam);
                $em->persist($levelJumpTeam);

                $newTeamLevel = new TeamLevel();

                $newTeamLevel->setTeam($currentTeam);
                $newTeamLevel->setStartTime(GameLogic::now());
                $newTeamLevel->setLevel($newLevel);

                $em->persist($newTeamLevel);
                $em->flush();

                /* @var GameSubjectRepository $repository */
                $repository = $em->getRepository("PacksAnSpielBundle:GameSubject");
                $gameSubjectList = $repository->getFourRandomGameSubjects();

                foreach ($gameSubjectList as $gameSubject) {
                    $teamLevelGame = new TeamLevelGame();
                    $teamLevelGame->setAssignedGameSubject($gameSubject);
                    $teamLevelGame->setStartTime(GameLogic::now());
                    $teamLevelGame->setTeamLevel($newTeamLevel);
                    $em->persist($teamLevelGame);
                    $em->flush();
                }
                $em->flush();

                return new RedirectResponse($this->generateUrl('packsan'));
            }

        }

        return $this->render('PacksAnSpielBundle::default/jump_level.html.twig',
            array('level_info' => $gameSubjectInfoList, 'team' => $currentTeam, 'error_message' => $errorMessage));
    }

    /**
     * @Route("/play/finished", name="playgamefinished")
     */
    public function gameFinishedAction(Request $request)
    {
        /*
         * TODO: Erfolgsnachricht, Feuerwerk!
         *
         * */
    }
}
