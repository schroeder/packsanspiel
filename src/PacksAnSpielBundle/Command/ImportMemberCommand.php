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
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getOption('file');

        $fs = new Filesystem();
        $em = $this->getContainer()->get('doctrine')->getEntityManager();

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
                            $output->writeln('<fg=yellow>Importing member ' . $data[14] . '</fg=yellow>');
                            $member = new Member();
                            $member->setPasscode($data[14]);
                        } else {
                            $output->writeln('<fg=blue>Updating member ' . $data[14] . '</fg=blue>');
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

            }
        } catch (IOExceptionInterface $e) {
            $output->writeln("<fg=red>Cannot read file!</fg=red>");
        }
        $output->writeln("<fg=green>Done!</fg=green>");
    }
}