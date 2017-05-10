<?php

namespace PacksAnSpielBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use PacksAnSpielBundle\Repository\MemberRepository;
use PacksAnSpielBundle\Repository\TeamRepository;
use PacksAnSpielBundle\Entity\Member;
use PacksAnSpielBundle\Entity\Team;
use PacksAnSpielBundle\Entity\Level;
use PacksAnSpielBundle\Game\GameLogic;

class ImportMemberCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('packsan:importmember')
            ->setDescription('Import the member.')
            ->addOption(
                'file',
                'f',
                InputOption::VALUE_REQUIRED,
                'File to import',
                false
            )->addOption(
                'rebuild-teams',
                'r',
                InputOption::VALUE_NONE,
                '(Re)build the teams'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getOption('file');
        $rebuildTeams = $input->getOption('rebuild-teams');

        $fs = new Filesystem();
        $em = $this->getContainer()->get('doctrine')->getEntityManager();

        if ($rebuildTeams) {
            $output->writeln("<fg=yellow>Teams will be rebuild!</fg=yellow>");
        }

        $output->writeln('Importing file ' . $file);

        try {
            $fs->exists($file);
        } catch (IOExceptionInterface $e) {
            $output->writeln("<fg=red>File not found!</fg=red>");
        }

        $memberRepository = $em->getRepository("PacksAnSpielBundle:Member");
        $teamRepository = $em->getRepository("PacksAnSpielBundle:Team");

        $teamBuilderMemberList = [];

        try {
            $row = 1;
            if (($handle = fopen($file, "r")) !== false) {
                while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                    if ($row > 1) {
                        $member = $memberRepository->findOneByPasscode($data[14]);

                        if (!$member) {
                            $output->writeln('Importing member ' . $data[14]);
                            $member = new Member();
                            $member->setPasscode($data[14]);
                        } else {
                            $output->writeln('Updating member ' . $data[14]);
                        }

                        $member->setName($data[2]);
                        $member->setFirstName($data[3]);
                        $grade = false;
                        switch ($data[5]) {
                            case "W":
                                $grade = 'w';
                                break;
                            case "J":
                                $grade = 'j';
                                break;
                            case "Pf":
                                $grade = 'p';
                                break;
                            case "R":
                                $grade = 'r';
                                break;
                            case "LW":
                            case "LJ":
                            case "LPf":
                            case "LR":
                                $grade = 'l';
                                break;
                            default:
                                $grade = 's';
                                break;
                        }
                        $member->setGrade($grade);
                        $group = $data[1];
                        $member->setGroup($group);
                        $village = trim(substr($data[6], 0, strpos($data[6], '/')));
                        $member->setVillage($village);

                        $em->persist($member);
                        $em->flush();

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
                    $row++;
                }
                fclose($handle);

                $teamListPool = [];

                // TODO: Form team
                foreach ($teamBuilderMemberList as $village => $villageList) {
                    $output->writeln('Building teams for village ' . $village);
                    foreach ($villageList as $group => $groupList) {
                        $output->writeln('Building teams for group ' . $group);

                        foreach ($groupList as $grade => $groupMember) {

                            if (in_array($grade, ['w', 'j', 'p', 'r'])) {
                                $output->writeln('Building teams for grade ' . $grade);

                                $teamList = array_chunk($groupMember, 4);
                                if (count($teamList[count($teamList) - 1]) < 3 && count($teamList) >= 3) {
                                    foreach ($teamList[count($teamList) - 1] as $mid => $member) {
                                        $teamList[$mid][] = $member;
                                    }
                                    unset($teamList[count($teamList) - 1]);
                                }
                                foreach ($teamList as $key => $memberList) {
                                    if (count($memberList) < 3) {
                                        if (!array_key_exists($grade, $teamListPool)) {
                                            $teamListPool[$grade] = [];
                                        }
                                        $teamListPool[$grade][] = $teamList[$key];
                                        unset($teamList[$key]);
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

                                        $gameLogic = $this->getContainer()->get('packsan.game.logic');
                                        $gameLogic->initializeFirstLevel($team);
                                    }

                                }

                            }
                        }
                        var_dump($teamListPool);
                    }
                }

                // Assign first game


            }
        } catch (IOExceptionInterface $e) {
            $output->writeln("<fg=red>Cannot read file!</fg=red>");
        }
        $output->write("<fg=green>Done!</fg=green>");
    }
}