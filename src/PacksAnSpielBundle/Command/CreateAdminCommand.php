<?php

namespace PacksAnSpielBundle\Command;

use PacksAnSpielBundle\Entity\TeamLevelGame;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use PacksAnSpielBundle\Repository\MemberRepository;
use PacksAnSpielBundle\Repository\TeamRepository;
use PacksAnSpielBundle\Entity\Member;
use PacksAnSpielBundle\Entity\Team;
use PacksAnSpielBundle\Game\GameLogic;

class CreateAdminCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('packsan:create_admin')
            ->setDescription('Let the game begin!')
            ->addOption(
                'name',
                false,
                InputOption::VALUE_REQUIRED,
                'Let the game begin!'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $adminName = $input->getOption('name');

        if ($adminName) {
            $output->writeln('<fg=green>Creating admin ' . $adminName . '</fg=green>');

            $em = $this->getContainer()->get('doctrine')->getEntityManager();

            $team = new Team();
            $team->setPasscode(md5(time() + rand(0, 1000) . rand(0, 1000)));
            $team->setStatus(Team::STATUS_ADMIN);
            $team->setGrade('a');
            $em->persist($team);


            $member = new Member();
            $member->setPasscode(md5(time() + rand(0, 1000) . rand(0, 1000)));
            $member->setGrade('a');
            $member->setName($adminName);
            $member->setFirstName($adminName);
            $member->setTeam($team);

            $em->persist($member);
            $em->flush();


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
}