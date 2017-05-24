<?php

namespace PacksAnSpielBundle\Controller;

use PacksAnSpielBundle\Entity\GameSubject;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use PacksAnSpielBundle\Repository\GameSubjectRepository;
use PacksAnSpielBundle\Entity\Team;
use PacksAnSpielBundle\Entity\TeamLevel;
use PacksAnSpielBundle\Entity\TeamLevelGame;
use PacksAnSpielBundle\Repository\TeamLevelRepository;
use PacksAnSpielBundle\Repository\TeamRepository;
use PacksAnSpielBundle\Repository\WordRepository;
use PacksAnSpielBundle\Game\GameLogic;
use PacksAnSpielBundle\Repository\GameRepository;
use PacksAnSpielBundle\Repository\MessageRepository;
use PacksAnSpielBundle\Entity\Message;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use PacksAnSpielBundle\Entity\Actionlog;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="packsan")
     */
    public function indexAction(Request $request)
    {
        /* @var GameActionLogger $logger */
        $logger = $this->get('packsan.action.logger');

        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return new RedirectResponse($this->generateUrl('login'));
        }

        /* @var Team $currentTeam */
        $currentTeam = $this->get('security.token_storage')->getToken()->getUser();

        $doctrine = $this->getDoctrine();

        /* @var TeamRepository $teamRepository */
        $teamRepository = $doctrine->getRepository("PacksAnSpielBundle:Team");

        /* @var TeamLevelRepository $teamLevelRepository */
        $teamLevelRepository = $doctrine->getRepository("PacksAnSpielBundle:TeamLevel");

        /* @var GameRepository $gameRepository */
        $gameRepository = $doctrine->getRepository("PacksAnSpielBundle:Game");

        /* @var Team $currentTeam */
        $currentTeam = $teamRepository->find($currentTeam->getId());

        $teamCanSetJoker = false;

        /* @var MessageRepository $messageRepository */
        $messageRepository = $doctrine->getRepository("PacksAnSpielBundle:Message");

        /* @var Message $message */
        $message = $messageRepository->findOneByTeam($currentTeam->getId());

        if ($message) {
            return new RedirectResponse($this->generateUrl('show_message'));
        }

        if ($currentTeam->getCurrentLevel() != null) {
            /* @var TeamLevel $currentTeamLevel */
            $currentTeamLevel = $teamLevelRepository->getCurrentTeamLevel($currentTeam, $currentTeam->getCurrentLevel());

            if (!$currentTeamLevel) {

                $logger->logAction("Cannot find current team level", Actionlog::LOGLEVEL_INFO, $currentTeam);

                return $this->render('PacksAnSpielBundle::message/error.html.twig',
                    array('message' => "Irgendwas ist schief gelaufen!"));

            }

            $gameSubjectInfoList = $currentTeamLevel->getTeamLevelInfo();
            $errorMessage = false;

            if ($currentTeam->getCurrentLevel()->getNumber() <= 2 &&
                !$teamRepository->teamAlreadyUsedJoker($currentTeam->getId())
            ) {
                $teamCanSetJoker = true;
            }

            if ($gameSubjectInfoList['count_games_won'] >= 2) {
                return $this->render('PacksAnSpielBundle::default/jump_level.html.twig',
                    array('level_info' => $gameSubjectInfoList, 'team' => $currentTeam, 'error_message' => $errorMessage));

            }

            if (isset($gameSubjectInfoList['current_game'])) {


                if ($gameSubjectInfoList['current_game'] && $gameSubjectInfoList['game_duration'] < 5 * 60) {
                    return $this->render('PacksAnSpielBundle::default/currently_playing.html.twig',
                        array('level_info' => $gameSubjectInfoList, 'team' => $currentTeam));

                } elseif ($gameSubjectInfoList['current_game']) {

                    /* @var WordRepository $wordRepository */
                    $wordRepository = $doctrine->getRepository("PacksAnSpielBundle:Word");

                    $resultOptions[] = $wordRepository->getOneRandom()->getName();
                    $resultOptions[] = $wordRepository->getOneRandom()->getName();
                    $resultOptions[] = $wordRepository->getOneRandom()->getName();
                    $resultOptions[] = $gameSubjectInfoList['current_game']->getGameAnswer();
                    shuffle($resultOptions);
                    $gameSubjectInfoList['result_options'] = $resultOptions;


                    return $this->render('PacksAnSpielBundle::default/enter_result.html.twig',
                        array('level_info' => $gameSubjectInfoList, 'team' => $currentTeam));

                }
            }

        } else {
            return new RedirectResponse($this->generateUrl('login'));
        }

        return $this->render('PacksAnSpielBundle::default/index.html.twig',
            array('level_info' => $gameSubjectInfoList, 'team' => $currentTeam, 'can_set_joker' => $teamCanSetJoker));
    }

    /**
     * @Route("/check_result", name="check_result")
     */
    public
    function checkResultAction(Request $request)
    {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return new RedirectResponse($this->generateUrl('login'));
        }

        /* @var Team $currentTeam */
        $currentTeam = $this->get('security.token_storage')->getToken()->getUser();

        $doctrine = $this->getDoctrine();

        /* @var TeamRepository $teamRepository */
        $teamRepository = $doctrine->getRepository("PacksAnSpielBundle:Team");

        /* @var TeamLevelRepository $teamLevelRepository */
        $teamLevelRepository = $doctrine->getRepository("PacksAnSpielBundle:TeamLevel");

        /* @var Team $currentTeam */
        $currentTeam = $teamRepository->find($currentTeam->getId());

        /* @var TeamLevel $currentTeamLevel */
        $currentTeamLevel = $teamLevelRepository->getCurrentTeamLevel($currentTeam, $currentTeam->getCurrentLevel());

        $gameSubjectInfoList = $currentTeamLevel->getTeamLevelInfo();

        /* @var WordRepository $wordRepository */
        $wordRepository = $doctrine->getRepository("PacksAnSpielBundle:Word");

        $solution = $request->get('solution');

        $message = false;
        if ($solution) {
            if ($solution == $gameSubjectInfoList['current_game']->getGameAnswer()) {
                $em = $doctrine->getEntityManager();
                /* @var TeamLevelGame $teamLevelGame */
                $teamLevelGame = $gameSubjectInfoList['current_team_level_game'];
                $teamLevelGame->setFinishTime(GameLogic::now());
                $teamLevelGame->setPlayedPoints(GameLogic::getPlayedPoints($currentTeam->getCurrentLevel()->getNumber()));
                $em->persist($teamLevelGame);
                $em->flush();

                return new RedirectResponse($this->generateUrl('packsan'));
            } else {
                $message = "Die Antwort war leider falsch!";
            }
        }


        $resultOptions[] = $wordRepository->getOneRandom()->getName();
        $resultOptions[] = $wordRepository->getOneRandom()->getName();
        $resultOptions[] = $wordRepository->getOneRandom()->getName();
        $resultOptions[] = $gameSubjectInfoList['current_game']->getGameAnswer();
        shuffle($resultOptions);
        $gameSubjectInfoList['result_options'] = $resultOptions;

        return $this->render('PacksAnSpielBundle::default/enter_result.html.twig',
            array('level_info' => $gameSubjectInfoList, 'team' => $currentTeam, 'message' => $message));
    }

    /**
     * @Route("/enter_joker", name="enter_joker")
     */
    public
    function enterJokerAction(Request $request)
    {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return new RedirectResponse($this->generateUrl('login'));
        }

        /* @var Team $currentTeam */
        $currentTeam = $this->get('security.token_storage')->getToken()->getUser();

        $doctrine = $this->getDoctrine();

        /* @var TeamRepository $teamRepository */
        $teamRepository = $doctrine->getRepository("PacksAnSpielBundle:Team");

        /* @var Team $currentTeam */
        $currentTeam = $teamRepository->find($currentTeam->getId());

        if ($currentTeam->getCurrentLevel() &&
            $currentTeam->getCurrentLevel()->getNumber() <= 2 &&
            !$teamRepository->teamAlreadyUsedJoker($currentTeam->getId())
        ) {
            return $this->render('PacksAnSpielBundle::joker/index.html.twig',
                array('team' => $currentTeam));


        } else {
            return new RedirectResponse($this->generateUrl('login'));
        }
    }

    /**
     * @Route("/show_message", name="show_message")
     */
    public
    function showMessageAction(Request $request)
    {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return new RedirectResponse($this->generateUrl('login'));
        }

        /* @var Team $currentTeam */
        $currentTeam = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getEntityManager();

        /* @var TeamRepository $teamRepository */
        $teamRepository = $em->getRepository("PacksAnSpielBundle:Team");
        /* @var TeamLevelRepository $teamLevelRepository */
        $teamLevelRepository = $em->getRepository("PacksAnSpielBundle:TeamLevel");

        /* @var Team $currentTeam */
        $currentTeam = $teamRepository->find($currentTeam->getId());

        /* @var TeamLevel $currentTeamLevel */
        $currentTeamLevel = $teamLevelRepository->getCurrentTeamLevel($currentTeam, $currentTeam->getCurrentLevel());

        $gameSubjectInfoList = $currentTeamLevel->getTeamLevelInfo();

        /* @var MessageRepository $messageRepository */
        $messageRepository = $em->getRepository("PacksAnSpielBundle:Message");

        /* @var Message $message */
        $message = $messageRepository->findOneByTeam($currentTeam->getId());

        if ($message) {

            $message->setReadTime(GameLogic::now());
            $em->persist($message);
            $em->flush();

            return $this->render('PacksAnSpielBundle::message/index.html.twig',
                array('team' => $currentTeam, 'level_info' => $gameSubjectInfoList, 'message' => $message));


        } else {
            return new RedirectResponse($this->generateUrl('packsan'));
        }
    }

    /**
     * @Route("/show_game_message", name="show_game_message")
     */
    public
    function showGameMessageAction(Request $request)
    {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_GAME')) {
            return new RedirectResponse($this->generateUrl('login'));
        }

        /* @var Session $session */
        $session = $request->getSession();
        $gameId = $session->get('game_id');

        $em = $this->getDoctrine()->getEntityManager();

        /* @var MessageRepository $messageRepository */
        $messageRepository = $em->getRepository("PacksAnSpielBundle:Message");

        /* @var Message $message */
        $message = $messageRepository->findOneByGame($gameId);

        if ($message) {

            $message->setReadTime(GameLogic::now());
            $em->persist($message);
            $em->flush();

            return $this->render('PacksAnSpielBundle::message/game.html.twig',
                array('message' => $message));


        } else {
            return new RedirectResponse($this->generateUrl('gameadmin'));
        }
    }
}
