<?php

namespace PacksAnSpielBundle\Controller;

use PacksAnSpielBundle\Entity\GameSubject;
use PacksAnSpielBundle\Entity\TeamLevelGame;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use PacksAnSpielBundle\Repository\GameSubjectRepository;
use PacksAnSpielBundle\Entity\Team;
use PacksAnSpielBundle\Repository\TeamLevelRepository;
use PacksAnSpielBundle\Repository\TeamRepository;

class PlayGameController extends Controller
{
    /**
     * @Route("/play/selectGame", name="play_select_gama")
     * @Method({"POST"})
     */
    public function indexAction(Request $request)
    {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return new RedirectResponse($this->generateUrl('login'));
        }

        $selectedCategory = $request->get('game_category');

        /* @var Team $currentTeam */
        $currentTeam = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine();

        /* @var GameSubjectRepository $repository */
        $gameSubject = $em->getRepository("PacksAnSpielBundle:GameSubject");

        var_dump($gameSubject);
        /* @var TeamRepository $repository */
        $teamRepository = $em->getRepository("PacksAnSpielBundle:Team");

        /* @var TeamLevelRepository $repository */
        $teamLevelRepository = $em->getRepository("PacksAnSpielBundle:TeamLevel");

        /* @var Team $currentTeam */
        $currentTeam = $teamRepository->find($currentTeam->getId());

        /* @var TeamLevel $currentTeamLevel */
        $currentTeamLevel = $teamLevelRepository->findCurrentTeamLevel($currentTeam, $currentTeam->getCurrentLevel());

        $gameSubjectList = [];
        /* @var TeamLevelGame $teamLevelGame */
        foreach ($currentTeamLevel->getTeamLevelGames() as $teamLevelGame) {
            $gameSubjectList[] = $teamLevelGame->getAssignedGameSubject();
        }

        return $this->render('PacksAnSpielBundle::default/index.html.twig',
            array('subject_list' => $gameSubjectList, 'team' => $currentTeam));
    }
}
