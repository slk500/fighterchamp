<?php

namespace AppBundle\Command;

use AppBundle\Entity\Fight;
use AppBundle\Entity\SignUpTournament;
use AppBundle\Entity\Tournament;
use AppBundle\Entity\UserFight;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SetAllPaidIfFightExistCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:set_all_paid_if_fight_exist')
            ->setDescription('Hello PhpStorm');

        $this

            ->addArgument('tournamentId', InputArgument::REQUIRED, 'tournamentId')

        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $tournamentId = $input->getArgument('tournamentId');

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $tournament = $em->getReference(Tournament::class, $tournamentId);

        $fights = $em->getRepository(Fight::class)->findAllFightsForTournamentAdmin($tournament);

        /**
         * @var $fight Fight
         */
        foreach ($fights as $fight) {
            /**
             * @var $userFight UserFight
             */
            foreach ($fight->getUsersFight() as $userFight) {
                $signUpTournament = $userFight->getUser()->getSignUpTournament($tournament);


                if (!$signUpTournament) {
                    var_dump($userFight->getUser()->getSurname());
                }

                /**
                 * @var $signUpTournament SignUpTournament
                 */
                if (! $signUpTournament->isPaid()) {
                    $signUpTournament->setIsPaid(true);
                }
            }
        }
        $em->flush();
    }
}
