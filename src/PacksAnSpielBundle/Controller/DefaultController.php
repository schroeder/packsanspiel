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

        /*
         * TODO: forward to message if there is one for the group
         * */

        if ($currentTeam->getCurrentLevel() != null) {
            /* @var TeamLevel $currentTeamLevel */
            $currentTeamLevel = $teamLevelRepository->getCurrentTeamLevel($currentTeam, $currentTeam->getCurrentLevel());

            $gameSubjectInfoList = $currentTeamLevel->getTeamLevelInfo();
            $errorMessage = false;

            /*
             * TODO: if two games played: Join with other team
             * */
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
            array('level_info' => $gameSubjectInfoList, 'team' => $currentTeam));
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
}
