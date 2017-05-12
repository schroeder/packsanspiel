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
use PacksAnSpielBundle\Entity\Game;
use PacksAnSpielBundle\Game\GameLogic;

class StartGameCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('packsan:start')
            ->setDescription('Let the game begin!')
            ->addOption(
                'start',
                false,
                InputOption::VALUE_NONE,
                'Let the game begin!'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $start = $input->getOption('start');

        if ($start) {
            $output->writeln('<fg=green>Staring the game</fg=green>');

            // TODO: set team status
            $em = $this->getContainer()->get('doctrine')->getEntityManager();

            $output->writeln('<fg=blue>  Activating teams</fg=blue>');
            $qb = $em->createQueryBuilder();
            $q = $qb->update('PacksAnSpielBundle:Team', 't')
                ->set('t.status', '?1')
                ->setParameter(1, Team::STATUS_ACTIVE)
                ->getQuery();
            $q->execute();

            $output->writeln('<fg=blue>  Activating team level</fg=blue>');
            $qb = $em->createQueryBuilder();
            $q = $qb->update('PacksAnSpielBundle:TeamLevel', 't')
                ->set('t.startTime', '?1')
                ->setParameter(1, GameLogic::now())
                ->getQuery();
            $q->execute();

            $output->writeln('<fg=blue>  Activating games</fg=blue>');
            $qb = $em->createQueryBuilder();
            $q = $qb->update('PacksAnSpielBundle:Game', 'g')
                ->set('g.status', '?1')
                ->setParameter(1, Game::STATUS_ACTIVE)
                ->getQuery();
            $q->execute();

            $output->writeln('<fg=blue>  Activating team level games</fg=blue>');
            $qb = $em->createQueryBuilder();
            $q = $qb->update('PacksAnSpielBundle:TeamLevelGame', 't')
                ->set('t.startTime', '?1')
                ->setParameter(1, GameLogic::now())
                ->getQuery();
            $q->execute();

        }

        die();

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

        // TODO: Assign first game

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
}