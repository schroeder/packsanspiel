<?php

namespace PacksAnSpielBundle\Command;

use PacksAnSpielBundle\Entity\TeamLevelGame;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use PacksAnSpielBundle\Repository\MemberRepository;
use PacksAnSpielBundle\Repository\TeamLevelRepository;
use PacksAnSpielBundle\Repository\GameRepository;
use PacksAnSpielBundle\Repository\TeamRepository;
use PacksAnSpielBundle\Entity\Member;
use PacksAnSpielBundle\Entity\Team;
use PacksAnSpielBundle\Entity\TeamLevel;
use PacksAnSpielBundle\Entity\Game;
use PacksAnSpielBundle\Entity\Level;
use PacksAnSpielBundle\Game\GameLogic;
use FPDF;

class BuildTeamsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('packsan:buildteams')
            ->setDescription('Build up the teams.')
            ->addOption(
                'build-teams',
                'b',
                InputOption::VALUE_NONE,
                'build the teams')
            ->addOption(
                'rebuild-teams',
                'r',
                InputOption::VALUE_NONE,
                '(Re)build the teams')
            ->addOption(
                'build-pdf',
                'p',
                InputOption::VALUE_NONE,
                'build PDF');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $buildTeams = $input->getOption('build-teams');
        $rebuildTeams = $input->getOption('rebuild-teams');
        $buildPdf = $input->getOption('build-pdf');

        $output->writeln('<fg=green>Build teams</fg=green>');

        $em = $this->getContainer()->get('doctrine')->getEntityManager();

        if ($rebuildTeams) {
            $output->write('<fg=magenta>Resetting all teams </fg=magenta>');
            if ($this->truncateTable(["PacksAnSpielBundle\Entity\TeamLevelGame", "PacksAnSpielBundle\Entity\TeamLevel", "PacksAnSpielBundle\Entity\Team"])) {
                $output->write("<fg=green>OK</fg=green>", true);
            } else {
                $output->write("<fg=red>Failed</fg=red>", true);
            }
        }
        if ($buildTeams) {

            $memberRepository = $em->getRepository("PacksAnSpielBundle:Member");

            $teamBuilderMemberList = [];
            $memberList = $memberRepository->findByGrade(['w', 'j', 'p', 'r']);
            /*
             * @var Member $member
             * */
            foreach ($memberList as $member) {
                $village = $member->getVillage();
                $grade = $member->getGrade();
                $group = $member->getGroup();
                if (!array_key_exists($village, $teamBuilderMemberList)) {
                    $teamBuilderMemberList[$village] = [];
                }
                if (!array_key_exists($group, $teamBuilderMemberList[$village])) {
                    $teamBuilderMemberList[$village][$group] = [];
                }
                if (!array_key_exists($grade, $teamBuilderMemberList[$village][$group])) {
                    $teamBuilderMemberList[$village][$group][$grade] = [];
                }
                $teamBuilderMemberList[$village][$group][$grade][] = $member;
            }

            foreach ($teamBuilderMemberList as $village => $villageList) {
                $teamListPool = [];
                $output->writeln('<fg=blue>Building teams for village ' . $village . '</fg=blue>');
                foreach ($villageList as $group => $groupList) {
                    $output->writeln('<fg=yellow>  Building teams for group ' . $group . '</fg=yellow>');

                    foreach ($groupList as $grade => $groupMember) {

                        $output->writeln('<fg=magenta>    Building teams for grade ' . $grade . '</fg=magenta>');

                        if (count($groupMember) == 6) {
                            $teamList = array_chunk($groupMember, 3);
                        } else {
                            $teamList = array_chunk($groupMember, 4);
                        }
                        $teamListCount = count($teamList);
                        if (count($teamList[count($teamList) - 1]) < 3 && count($teamList) >= 3) {
                            foreach ($teamList[count($teamList) - 1] as $mid => $member) {
                                $teamList[$mid][] = $member;
                            }
                            unset($teamList[count($teamList) - 1]);
                        }
                        $teamsInThisRound = [];
                        foreach ($teamList as $key => $memberList) {
                            if ($teamListCount == 1 && count($memberList) < 3) {
                                $output->writeln('<fg=cyan>      Put small group into pool</fg=cyan>');
                                if (!array_key_exists($grade, $teamListPool)) {
                                    $teamListPool[$grade] = [];
                                }
                                foreach ($memberList as $index => $member) {
                                    $teamListPool[$grade][] = $member;
                                }
                                unset($teamList[$key]);
                            } elseif (count($memberList) < 3) {
                                $output->writeln('<fg=cyan>      Put member to full groups.</fg=cyan>');
                                if (count($teamsInThisRound) <= count($memberList)) {
                                    foreach ($memberList as $index => $member) {
                                        $member->setTeam($teamsInThisRound[$index]);
                                        $em->persist($member);
                                        $em->flush();
                                    }
                                } else {
                                    $output->writeln('<fg=cyan>      Put small group into pool</fg=cyan>');
                                    if (!array_key_exists($grade, $teamListPool)) {
                                        $teamListPool[$grade] = [];
                                    }
                                    foreach ($memberList as $index => $member) {
                                        $teamListPool[$grade][] = $member;
                                    }
                                }
                            } else {
                                $team = new Team();
                                $team->setGrade($grade);
                                $team->setStatus(0);
                                $team->setPasscode(md5(GameLogic::now() + rand(0, 299)));
                                $em->persist($team);
                                $em->flush();

                                foreach ($memberList as $member) {
                                    $member->setTeam($team);
                                    $em->persist($member);
                                    $em->flush();
                                }
                                $teamsInThisRound[] = $team;

                                $gameLogic = $this->getContainer()->get('packsan.game.logic');
                                $gameLogic->initializeFirstLevel($team, true);
                            }
                        }
                    }
                } // end foreach group
                if (count($teamListPool)) {
                    $output->writeln('<fg=cyan>  Merge small groups of village</fg=cyan>');
                    foreach ($teamListPool as $grade => $groupMember) {
                        $output->writeln('<fg=magenta>    Merging teams for grade ' . $grade . '</fg=magenta>');

                        if (count($groupMember) == 6) {
                            $teamList = array_chunk($groupMember, 3);
                        } else {
                            $teamList = array_chunk($groupMember, 4);
                        }

                        foreach ($teamList as $key => $memberList) {
                            $team = new Team();
                            $team->setGrade($grade);
                            $team->setStatus(0);
                            $team->setPasscode(md5(GameLogic::now() + rand(0, 299)));
                            $em->persist($team);
                            $em->flush();

                            foreach ($memberList as $member) {
                                $member->setTeam($team);
                                $em->persist($member);
                                $em->flush();
                            }
                            $teamsInThisRound[] = $team;

                            /* @var GameLogic $gameLogic */
                            $gameLogic = $this->getContainer()->get('packsan.game.logic');
                            $gameLogic->initializeFirstLevel($team, true);
                        }
                    }
                }
                $output->writeln('');
            } // end foreach village

            $output->writeln('');
            $output->writeln('');

            $output->writeln('<fg=green>Assign first game</fg=green>');

            /* @var GameRepository $gameRepository */
            $gameRepository = $em->getRepository("PacksAnSpielBundle:Game");

            /* @var TeamLevelRepository $teamLevelRepository */
            $teamLevelRepository = $em->getRepository("PacksAnSpielBundle:TeamLevel");

            /* @var TeamLevelGameRepository $teamLevelGameRepository */
            $teamLevelGameRepository = $em->getRepository("PacksAnSpielBundle:TeamLevelGame");

            $teamLevelList = $teamLevelRepository->findByLevel(1);
            foreach ($teamLevelList as $teamLevel) {
                $output->write('<fg=cyan>  Assign game for group ' . $teamLevel->getTeam()->getId() . ': </fg=cyan>');
                $currentGame = $gameRepository->findAFreeGame($teamLevel);
                if ($currentGame) {
                    $teamLevelGame = $teamLevelGameRepository->findOneByTeamLevel($teamLevel->getId());
                    $teamLevelGame->setAssignedGame($currentGame);
                    $teamLevelGame->setStartTime(GameLogic::now());
                    $em->persist($teamLevelGame);
                    $em->flush();

                    $output->write('<fg=green>OK</fg=green>', true);
                } else {
                    $output->write('<fg=red>Failed</fg=red>', true);

                }
            }
        }

        if ($buildPdf) {
            $this->generateTeamPdf($output, $em);
        }


        $output->writeln("<fg=green>Done!</fg=green>");
    }

    private function truncateTable($classList)
    {
        $em = $this->getContainer()->get('doctrine')->getEntityManager();

        try {
            $connection = $em->getConnection();
            $connection->beginTransaction();
            foreach ($classList as $className) {
                $cmd = $em->getClassMetadata($className);
                $connection->query('SET FOREIGN_KEY_CHECKS=0');
                $connection->query('DELETE FROM ' . $cmd->getTableName());
                $connection->query('SET FOREIGN_KEY_CHECKS=1');
            }
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollback();
            return false;
        }
        return true;
    }

    private function generateTeamPdf(OutputInterface $output, $em)
    {
        $temp_file = tempnam(sys_get_temp_dir(), 'packs_an_joker_') . '.png';

        $logo_file = "src/PacksAnSpielBundle/Resources/public/images/logo_gross.png";

        /* @var TeamRepository $teamRepository */
        $teamRepository = $em->getRepository("PacksAnSpielBundle:Team");

        /* @var TeamLevelRepository $teamLevelRepository */
        $teamLevelRepository = $em->getRepository("PacksAnSpielBundle:TeamLevel");

        $teamList = $teamRepository->findAll();

        $gameLogic = $this->getContainer()->get('packsan.game.logic');

        $pdf = new FPDF();
        $fontSize = 12;
        $fontFamily = 'helvetica';
        $pdf->SetFont($fontFamily, '', $fontSize);

        /* @var Team $team */
        foreach ($teamList as $team) {
            $output->writeln("<fg=blue>  Team " . $team->getId() . "</fg=blue>");
            $pdf->AddPage();

            $memberList = $team->getTeamMembers();

            $line = "";
            $pos = 50;
            $group = "";
            $grade = "";
            $firstNames = "";
            /* @var Member $member */
            if ($memberList) {
                foreach ($memberList->toArray() as $member) {

                    $village = utf8_decode($member->getVillage());
                    $grade = utf8_decode($member->getGrade());
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetFontSize(11);
                    if ($group != $member->getGroup()) {
                        $pdf->SetFontSize(10);
                        $pdf->Text(20, $pos, utf8_decode($member->getGroup()));
                        $pdf->Line(20, $pos + 1, 110, $pos + 1);
                        $pos += 6;
                        $group = $member->getGroup();
                    }
                    $pdf->Text(30, $pos, utf8_decode($member->getFullName()));
                    $firstNames .= utf8_decode($member->getFirstName()) . ", ";

                    $pos += 6;

                }
                $pdf->SetTextColor(190, 190, 190);
                $pdf->SetDrawColor(190, 190, 190);
                $pdf->Line(20, 45, 110, 45);
                $pdf->Text(40, 44, $village . " (" . utf8_decode($gameLogic->getGradename($grade)) . ")");
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetDrawColor(0, 0, 0);
                $pdf->SetFontSize(14);
            }

            $jokerText = $this->getContainer()->get('templating')->render('PacksAnSpielBundle::register/welcome.txt.twig', ['first_names' => $firstNames, "grade" => $gameLogic->getGradename($grade)]);

            $pdf->SetXY(20, 110);
            $pdf->SetFontSize(9);
            $pdf->MultiCell(180, 5, $jokerText, false, 'L');


            /* @var TeamLevel $currentTeamLevel */
            $currentTeamLevel = $teamLevelRepository->getCurrentTeamLevel($team, $team->getCurrentLevel());
            $gameSubjectInfoList = $currentTeamLevel->getTeamLevelInfo();

            /* @var TeamLevelGame $teamLevelGame */
            $game = $gameSubjectInfoList['current_game'];
            $gameSubject = $gameSubjectInfoList['current_team_level_game']->getAssignedGameSubject();
            if ($game) {
                $location = $game->getLocation();
                $gameSubjectText = $gameSubject->getName();
                $gameIdentifier = $game->getIdentifier();

                $pdf->SetFontSize(12);
                $pdf->Text(45, 200, "Euer erstes Spiel steht unter dem Motto:");
                $pdf->Text(45, 230, "Geht bitte zum Spielort und sucht euer Spiel!");


                $pdf->SetFontSize(20);
                $pdf->Text(45, 207, utf8_decode($gameSubjectText));
                $pdf->Text(45, 214, "(" . utf8_decode($gameIdentifier . ")"));
                $pdf->Text(45, 237, utf8_decode($location));

            }
            $pdf->Image($logo_file, 150, 10, 50, 50);

        }


        $pdf->Output('var/result.pdf', 'F');

    }

}