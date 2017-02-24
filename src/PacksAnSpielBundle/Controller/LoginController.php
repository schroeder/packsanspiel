<?php

namespace PacksAnSpielBundle\Controller;

use PacksAnSpielBundle\Entity\Actionlog;
use PacksAnSpielBundle\Entity\Game;
use PacksAnSpielBundle\Entity\Member;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use PacksAnSpielBundle\Game\GameActionLogger;
use PacksAnSpielBundle\Entity\Team;
use PacksAnSpielBundle\Repository\TeamRepository;
use PacksAnSpielBundle\Repository\MemberRepository;
use PacksAnSpielBundle\Repository\GameRepository;
use PacksAnSpielBundle\Game\GameLogic;
use Symfony\Component\HttpFoundation\Session\Session;

class LoginController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        /* @var GameActionLogger $logger */
        $logger = $this->get('packsan.action.logger');
        $errorMessage = false;

        $scannedQRCode = $request->get('qr');
        if ($scannedQRCode) {
            try {
                list($codeType, $scannedQRCode) = explode(':', $scannedQRCode);

            } catch (\Exception $e) {
                $codeType = false;
                $scannedQRCode = false;
            }

            if (!$codeType || !$scannedQRCode || $codeType == "" || $scannedQRCode == "") {
                $errorMessage = "Invalid code";
                $logger->logAction("Failed login try with qr code $scannedQRCode, error: \"$errorMessage\"", Actionlog::LOGLEVEL_WARN);
                return $this->render('PacksAnSpielBundle::login/index.html.twig', ["error_message" => $errorMessage]);
            }

            $em = $this->getDoctrine();

            $team = false;

            switch ($codeType) {
                case 'team':
                    $teamId = $scannedQRCode;
                    /* @var TeamRepository $repo */
                    $repo = $em->getRepository("PacksAnSpielBundle:Team");
                    /* @var Team $team */
                    $team = $repo->findLeadingGroup($teamId);

                    if (!$team) {
                        $logger->logAction("Failed team login try with qr code $scannedQRCode!", Actionlog::LOGLEVEL_WARN);
                        $errorMessage = "Das Team kenne ich leider nicht!";
                        break;
                    }

                    // Redirect to /register if number of members less than 3
                    if (count($team->getTeamMembers()) < 3) {
                        $this->get('session')->set('team', $team->getPasscode());
                        return new RedirectResponse($this->generateUrl('get_register') . "?action=init");
                    }
                    break;
                case 'member':
                    $memberId = $scannedQRCode;
                    /* @var MemberRepository $repo */
                    $repo = $em->getRepository("PacksAnSpielBundle:Member");
                    /* @var Member $member */
                    $member = $repo->findOneByPasscode($memberId);
                    if (!$member) {
                        $this->get('session')->set('member_list', $memberId);
                        return new RedirectResponse($this->generateUrl('get_register') . "?action=init");
                    }
                    $team = $member->getTeam();

                    if ($team) {
                        /* @var TeamRepository $repo */
                        $repo = $em->getRepository("PacksAnSpielBundle:Team");
                        /* @var Team $team */
                        $team = $repo->findLeadingGroup($team->getPasscode());
                    }

                    if (!$team) {
                        $this->get('session')->set('member_list', $memberId);
                        return new RedirectResponse($this->generateUrl('get_register') . "?action=init");
                    }
                    break;
                case 'admin':
                    $memberId = $scannedQRCode;
                    /* @var MemberRepository $repo */
                    $repo = $em->getRepository("PacksAnSpielBundle:Member");
                    /* @var Member $member */
                    $member = $repo->findAdminByPasscode($memberId);
                    if ($member && $member->getGrade() == "admin") {

                        $team = $member->getTeam();
                        if ($team && in_array("ROLE_ADMIN", $team->getRoles())) {
                            $token = new UsernamePasswordToken($team, null, "main", $team->getRoles());

                            //now the user is logged in
                            $this->get("security.token_storage")->setToken($token);

                            $event = new InteractiveLoginEvent($request, $token);
                            $this->get("event_dispatcher")->dispatch("security . interactive_login", $event);
                            $logger->logAction("Admin logged in . ", Actionlog::LOGLEVEL_INFO, $team);

                            return new RedirectResponse($this->generateUrl('admin'));
                        } else {
                            $team = false;
                        }
                    } else {
                        $logger->logAction("Failed admin login try with qr code $scannedQRCode!", Actionlog::LOGLEVEL_CRIT);
                        $errorMessage = "Den Teilnehmer kenne ich leider nicht!";
                    }
                    break;
                case 'game':
                    $gameId = $scannedQRCode;

                    /* @var GameRepository $gameRepo */
                    $gameRepo = $em->getRepository("PacksAnSpielBundle:Game");

                    /* @var Game $game */
                    $game = $gameRepo->findGameByPasscode($gameId);

                    if ($game) {
                        $gameAdminPasscode = $this->getParameter('packsan_game_admin_passcode');

                        /* @var TeamRepository $teamRepo */
                        $teamRepo = $em->getRepository("PacksAnSpielBundle:Team");

                        $team = $teamRepo->findOneByPasscode($gameAdminPasscode);
                        if ($team && in_array("ROLE_GAME", $team->getRoles())) {
                            $token = new UsernamePasswordToken($team, null, "main", $team->getRoles());

                            //now the user is logged in
                            $this->get("security.token_storage")->setToken($token);

                            /* @var Session $session */
                            $session = $request->getSession();
                            $session->set('game_id', $game->getId());
                            $session->set('game_passcode', $game->getPasscode());

                            $event = new InteractiveLoginEvent($request, $token);
                            $this->get("event_dispatcher")->dispatch("security . interactive_login", $event);
                            $logger->logAction("Game logged in . ", Actionlog::LOGLEVEL_INFO, $team);

                            return new RedirectResponse($this->generateUrl('gameadmin'));
                        } else {
                            $team = false;
                        }
                    } else {
                        $logger->logAction("Failed admin login try with qr code $scannedQRCode!", Actionlog::LOGLEVEL_CRIT);
                        $errorMessage = "Den Teilnehmer kenne ich leider nicht!";
                    }
                    break;
                case
                'joker':
                default:
                    $errorMessage = "Bitte Teilnehmerkarte oder Teamkarte in den Terminal führen!";
                    break;
            };

            if ($team) {
                try {
                    $token = new UsernamePasswordToken($team, null, "main", $team->getRoles());

                    //now the user is logged in
                    $this->get("security.token_storage")->setToken($token);

                    $event = new InteractiveLoginEvent($request, $token);
                    $this->get("event_dispatcher")->dispatch("security . interactive_login", $event);
                    $logger->logAction("Team logged in . ", Actionlog::LOGLEVEL_INFO, $team);

                    if ($team->getCurrentLevel() == false) {

                        /* @var GameLogic $gameLogic */
                        $gameLogic = $this->get('packsan.game.logic');
                        $gameLogic->initializeFirstLevel($team);
                        $logger->logAction("Moved Team into first level. ", Actionlog::LOGLEVEL_INFO, $team);
                    }

                    return new RedirectResponse($this->generateUrl('packsan'));
                } catch (\Exception $e) {
                    $logger->logAction("Unable to log in team . ", Actionlog::LOGLEVEL_CRIT, $team);
                    $errorMessage = "Ich kann euch leider nicht einloggen!";
                }
            }

            if ($errorMessage != false) {
                return $this->render('PacksAnSpielBundle::login/index.html.twig', ["error_message" => $errorMessage]);
            }

        }
        return $this->render('PacksAnSpielBundle::login/index.html.twig', ["error_message" => $errorMessage]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public
    function logoutAction(Request $request)
    {
        $this->get('security.token_storage')->setToken(null);
        $this->get('request')->getSession()->invalidate();
        return new RedirectResponse($this->generateUrl('login'));
    }
}
