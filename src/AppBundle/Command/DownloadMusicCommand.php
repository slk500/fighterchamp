<?php

namespace AppBundle\Command;

use AppBundle\Entity\SignUpTournament;
use AppBundle\Entity\Tournament;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DownloadMusicCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:download_music_command')
            ->setDescription('Hello PhpStorm');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $tournament = $em->getReference(Tournament::class, 8);

        $signUps = $em->getRepository(SignUpTournament::class)->findMusicForTournament($tournament);

        $string = '';
        $i = 0;

        foreach ($signUps as $signUp) {
            /**
             * @var $signUp SignUpTournament
             */
            $youtubeId = $signUp->getYoutubeId();

            $surname = $signUp->getUser()->getSurname();
            $name = $signUp->getUser()->getName();



            $string .= "youtube-dl $youtubeId -x --audio-format mp3 -o '$surname $name.%(ext)s' && ";

            $i++;
        }

        echo $string . PHP_EOL;
    }
}
