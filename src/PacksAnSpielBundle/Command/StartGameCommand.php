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