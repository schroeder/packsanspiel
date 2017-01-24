<?php

namespace PacksAnSpielBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use PacksAnSpielBundle\Game\GameActionLogger;
use PacksAnSpielBundle\Repository\MemberRepository;
use PacksAnSpielBundle\Repository\TeamRepository;
use PacksAnSpielBundle\Entity\Member;
use PacksAnSpielBundle\Entity\Team;
use PacksAnSpielBundle\Entity\Actionlog;

class RegistrationController extends BaseController
{
    /**
     * @Route("/register", name="register")
     */
    public function registerAction(Request $request)
    {
        /* @var GameActionLogger $logger */
        $logger = $this->get('packsan.action.logger');
        $errorMessage = false;

        $em = $this->getDoctrine();

        $action = $request->get('action');

        $team = false;
        $memberList = [];

        /* @var MemberRepository $repo */
        $memberRepo = $em->getRepository("PacksAnSpielBundle:Member");
        /* @var TeamRepository $repo */
        $teamRepo = $em->getRepository("PacksAnSpielBundle:Team");

        if ($this->get('session')->has('team')) {
            $teamId = $this->get('session')->get('team');
            $team = $teamRepo->findOneByPasscode($teamId);
        }

        if ($this->get('session')->has('member_list')) {
            $memberIdString = $this->get('session')->get('member_list');
            $memberIdList = explode(',', $memberIdString);
            foreach ($memberIdList as $memberId) {
                /* @var Member $member */
                $member = $memberRepo->findOneByPasscode($memberId);
                if ($member) {
                    $memberList[] = $member;
                } else {
                    $logger->logAction("Registration: Cannot find member with passcode $memberId.", Actionlog::LOGLEVEL_CRIT, $team);
                }
            }
        }

        $scannedQRCode = $request->get('qr');
        if ($scannedQRCode) {
            list($codeType, $scannedQRCode) = explode(':', $scannedQRCode);

            if (!$codeType || !$scannedQRCode || $codeType == "" || $scannedQRCode == "") {
                $errorMessage = "Invalid code";
                $logger->logAction("Failed login try with qr code $scannedQRCode, error: \"$errorMessage\"", Actionlog::LOGLEVEL_WARN);
                return new RedirectResponse($this->generateUrl('login'));
            }
        }

        switch ($action) {
            case 'init':
                break;
            case 'addMember':
                if ($codeType == 'member') {
                    $member = $memberRepo->findOneByPasscode($scannedQRCode);
                    if ($member) {
                        if (!in_array($member, $memberList)) {
                            $memberList[] = $member;
                            $memberIdList[] = $scannedQRCode;
                            $memberIdString = implode(',', array_unique($memberIdList));
                            $this->get('session')->set('member_list', $memberIdString);
                        } else {
                            $logger->logAction("Registration: member already registered", Actionlog::LOGLEVEL_WARN, $team);
                        }
                    } else {
                        $logger->logAction("Registration: Cannot find member to add $scannedQRCode.", Actionlog::LOGLEVEL_CRIT, $team);
                    }
                } else {
                    $logger->logAction("Registration: Cannot find member type to add: $codeType.", Actionlog::LOGLEVEL_CRIT, $team);
                }
                return new RedirectResponse($this->generateUrl('register') . "?action=init");
                break;
            case 'setTeam':
                if ($codeType == 'team') {
                    /* @var Team $team */
                    $team = $teamRepo->findOneByPasscode($scannedQRCode);
                    if (!$team) {
                        $logger->logAction("Registration: Cannot find team to add $scannedQRCode.", Actionlog::LOGLEVEL_WARN, $team);
                    }
                    $this->get('session')->set('team', $scannedQRCode);
                } else {
                    $logger->logAction("Registration: Cannot find member type to add: $codeType.", Actionlog::LOGLEVEL_CRIT, $team);
                }
                return new RedirectResponse($this->generateUrl('register') . "?action=init");
                break;
            case
            'removeMember':
                break;
            case 'finish':
                if (!($team || $this->get('session')->has('team')) || count($memberList) == 0) {
                    $logger->logAction("Registration: Cannot finish registration!", Actionlog::LOGLEVEL_CRIT);
                    break;
                }
                if (!$team) {
                    $team = $teamRepo->initializeNewTeam($this->get('session')->get('team'), Team::STATUS_ACTIVE)   ;
                }
                $teamRepo->setTeamMember($team, $memberList);
                $this->get('session')->remove('member_list');
                $this->get('session')->remove('team');
                return new RedirectResponse($this->generateUrl('login'));
        }

        return $this->render('PacksAnSpielBundle::register/index.html.twig',
            ["error_message" => $errorMessage,
                "member_list" => $memberList,
                "team" => $team,
                "team_id" => $this->get('session')->get('team')]
        );
    }
}