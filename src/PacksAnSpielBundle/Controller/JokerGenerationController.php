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
use PacksAnSpielBundle\Repository\TeamRepository;
use FPDF;
use Symfony\Component\HttpFoundation\Response;
use Endroid\QrCode\QrCode;

class JokerGenerationController extends Controller
{
    /**
     * @Route("/Packs-An-Joker", name="requestajoker")
     */
    public function indexAction(Request $request)
    {
        $qrCodeMessage = 'joker:' . md5(time() . '-' . rand(0, 100000));
        // http://blog.michaelperrin.fr/2016/02/17/generating-pdf-files-with-symfony/
        // TODO: Put Joker in db
        /* TODO: process:
         * if cookie set: Show message
         * if not: Show questionaire
         * POST: Correct : Generate joker, store, Show Joker, PDF to doenload, set cookie
         * Not correct: Show message
        */


        return $this->render('PacksAnSpielBundle::joker_generation/index.html.twig',
            array('qrcode_message' => $qrCodeMessage));
    }

    /**
     * @Route("/getJoker", name="getajoker")
     */
    public function getJokerAction(Request $request)
    {
        $qrCodeMessage = 'joker:' . md5(time() . '-' . rand(0, 100000));

        $qrCode = new QrCode();
        $qrCode->setText($qrCodeMessage);

        $temp_file = tempnam(sys_get_temp_dir(), 'packs_an_joker_') . '.png';

        $logo_file = "../src/PacksAnSpielBundle/Resources/public/images/logo_gross.png";

        $qrCode->save($temp_file);

        $jokerText = $this->render('PacksAnSpielBundle::joker_generation/joker.txt.twig',
            array('qrcode_message' => $qrCodeMessage));

        // TODO check if it is ok.
        $pdf = new FPDF();
        $pdf->AddPage();

        $pdf->SetFont('Arial', '', 12);
        $pdf->SetXY(15, 20);
        $pdf->MultiCell(120, 5, $jokerText->getContent(), false, 'L');
        $pdf->Image($temp_file, 150, 150, 60, 60);
        $pdf->Image($logo_file, 150, 10, 50, 50);
        $pdf->Line(145, 150, 210, 150);
        $pdf->Line(145, 210, 210, 210);
        $pdf->SetFontSize(9);
        $pdf->Text(145, 149, 'Bitte hier knicken!');
        $pdf->Text(145, 213, 'Hier auch knicken!');


        return new Response($pdf->Output(), 200, array(
            'Content-Type' => 'application/pdf'));
    }
}
