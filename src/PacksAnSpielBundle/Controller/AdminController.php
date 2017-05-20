<?php

namespace PacksAnSpielBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use PacksAnSpielBundle\Repository\MemberRepository;
use PacksAnSpielBundle\Entity\Member;
use PacksAnSpielBundle\Repository\TeamRepository;
use PacksAnSpielBundle\Repository\GameRepository;
use PacksAnSpielBundle\Entity\Team;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Endroid\QrCode\QrCode;
use FPDF;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin")
     */
    public function indexAction(Request $request)
    {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            return new RedirectResponse($this->generateUrl('login'));
        }

        return $this->render('PacksAnSpielBundle::admin/index.html.twig');
    }


    /**
     * @Route("/admin/member_passcode", name="admin_member_passcode")
     */
    public function listMemberPasscodeGenerationAction(Request $request)
    {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            return new RedirectResponse($this->generateUrl('login'));
        }
        $paginator = $this->get('knp_paginator');

        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);
        $offset = ($page * $limit) - $limit;

        $em = $this->getDoctrine();

        $memberList = [];

        /* @var MemberRepository $memberRepo */
        $memberRepo = $em->getRepository("PacksAnSpielBundle:Member");

        $fullMemberList = $memberRepo->findByMemberOnly(null);

        $memberList = $paginator->paginate(
            $fullMemberList,
            $page /*page number*/,
            $limit /*limit per page*/
        );

        return $this->render('PacksAnSpielBundle::admin/admin_member_passcode.html.twig', [
                "member_list" => $memberList,
                "page" => $page,
                "limit" => $limit,
                "total_count" => count($fullMemberList)
            ]
        );
    }

    /**
     * @Route("/admin/game_team_status", name="admingameteamstatus")
     */
    public function listGameTeamStatusAction(Request $request)
    {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            return new RedirectResponse($this->generateUrl('login'));
        }
        //$paginator = $this->get('knp_paginator');

        //$page = $request->query->getInt('page', 1);
        //$limit = $request->query->getInt('limit', 10);


        $em = $this->getDoctrine();

        $teamList = [];

        /* @var GameRepository $gameRepository */
        $gameRepository = $em->getRepository("PacksAnSpielBundle:Game");

        $fullTeamGameList = $this->getCurrentGameStatusList(true);

        /* $teamList = $paginator->paginate(
             $fullTeamGameList,
             $page
             $limit
         );*/
        return $this->render('PacksAnSpielBundle::admin/admin_game_team_status.html.twig', [
                "game_team_list" => $fullTeamGameList,
                /*"page" => $page,
                "limit" => $limit,*/
                "total_count" => count($fullTeamGameList)
            ]
        );
    }


    /**
     * @Route("/admin/generate_member_passcode/{id}", name="admin_generate_member_passcode", requirements={"id" = "\d+"})
     */
    public function generateMemberPasscodeAction($id, Request $request)
    {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            return new RedirectResponse($this->generateUrl('login'));
        }

        $em = $this->getDoctrine();

        /* @var MemberRepository $repo */
        $memberRepo = $em->getRepository("PacksAnSpielBundle:Member");

        /* @var Member $member */
        $member = $memberRepo->find($id);


        $qrCodeMessage = $member->getPasscode();

        $qrCode = new QrCode();
        $qrCode->setText($qrCodeMessage);

        $temp_file = tempnam(sys_get_temp_dir(), 'packs_an_member_') . '.png';

        $qrCode->save($temp_file);

        // TODO check if it is ok.
        $pdf = new FPDF();
        $pdf->AddPage();

        $pdf->SetFont('Arial', '', 16);
        $pdf->Text(15, 26, "Teilnehmerkarte");

        $pdf->SetFont('Arial', '', 12);
        $pdf->Text(15, 36, "Name:");
        $pdf->Text(45, 36, utf8_decode($member->getFullName()));
        $pdf->Text(15, 46, "Passcode:");
        $pdf->Text(45, 46, $member->getPasscode());
        $pdf->Text(15, 56, "Team:");
        if ($member->getTeam()) {
            $pdf->Text(45, 56, $member->getTeam()->getPasscode());
        } else {
            $pdf->Text(45, 56, "./.");
        }
        $pdf->SetXY(15, 20);
        $pdf->Image($temp_file, 140, 15, 60, 60);
        $pdf->Line(15, 15, 195, 15);
        $pdf->Line(15, 75, 195, 75);


        return new Response($pdf->Output(), 200, array(
            'Content-Type' => 'application/pdf'));
    }

    /**
     * @Route("/admin/team_passcode", name="admin_team_passcode")
     */
    public function listTeamPasscodeGenerationAction(Request $request)
    {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            return new RedirectResponse($this->generateUrl('login'));
        }
        $paginator = $this->get('knp_paginator');

        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);


        $em = $this->getDoctrine();

        $teamList = [];

        /* @var TeamRepository $teamRepo */
        $teamRepo = $em->getRepository("PacksAnSpielBundle:Team");

        $fullTeamList = $teamRepo->findBy(array(), null, 100, 0);

        $teamList = $paginator->paginate(
            $fullTeamList,
            $page /*page number*/,
            $limit /*limit per page*/
        );

        return $this->render('PacksAnSpielBundle::admin/admin_team_passcode.html.twig', [
                "team_list" => $teamList,
                "page" => $page,
                "limit" => $limit,
                "total_count" => count($fullTeamList)
            ]
        );
    }

    /**
     * @Route("/admin/generate_team_passcode/{id}", name="admin_generate_team_passcode", requirements={"id" = "\d+"})
     */
    public function generateTeamPasscodeAction($id, Request $request)
    {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            return new RedirectResponse($this->generateUrl('login'));
        }

        $em = $this->getDoctrine();

        /* @var MemberRepository $repo */
        $teamRepo = $em->getRepository("PacksAnSpielBundle:Team");

        /* @var Team $team */
        $team = $teamRepo->find($id);


        $qrCodeMessage = 'team:' . $team->getPasscode();

        $qrCode = new QrCode();
        $qrCode->setText($qrCodeMessage);

        $temp_file = tempnam(sys_get_temp_dir(), 'packs_an_team_') . '.png';

        $qrCode->save($temp_file);

        // TODO check if it is ok.
        $pdf = new FPDF();
        $pdf->AddPage();

        $pdf->SetFont('Arial', '', 16);
        $pdf->Text(15, 36, "Teamkarte");

        $pdf->SetFont('Arial', '', 12);
        $pdf->Text(15, 46, "Passcode:");
        $pdf->Text(45, 46, $team->getPasscode());
        $pdf->Text(15, 56, "Team Mitglieder:");
        if ($team->getCountMembers()) {
            $pdf->Text(45, 56, $team->getCountMembers());
        } else {
            $pdf->Text(45, 56, "0");
        }
        $pdf->SetXY(15, 20);
        $pdf->Image($temp_file, 140, 15, 60, 60);
        $pdf->Line(15, 15, 195, 15);
        $pdf->Line(15, 75, 195, 75);


        return new Response($pdf->Output(), 200, array(
            'Content-Type' => 'application/pdf'));
    }

    private function getCurrentGameStatusList($all = false)
    {
        $conn = $this->get('database_connection');

        $queryString = "SELECT g.id as game_id, t.id as team_id, g.level_id, g.identifier, g.name, g.grade, 
                            g.duration AS planned_duration, 
                            (UNIX_TIMESTAMP()-tlg.start_time)/60 AS game_duration, 
                            (UNIX_TIMESTAMP()-tl.start_time)/60 AS level_duration  
                        FROM game g
                        LEFT JOIN team_level_game tlg 
                            ON g.id=tlg.assigned_game 
                        LEFT JOIN team_level tl 
                            ON tlg.team_level_id=tl.id 
                        LEFT JOIN team t
                            ON tl.team_id=t.id
                        WHERE tlg.finish_time IS NULL";

        if ($all) {
            $queryString .= " AND t.id IS NOT NULL";
        }
        $result = $conn->executeQuery($queryString);
        $teamGameList = $result->fetchAll();

        return $teamGameList;

    }
}
