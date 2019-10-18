<?php

namespace AppBundle\Command;

use AppBundle\Entity\Fight;
use AppBundle\Entity\Tournament;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InsertYouTubeIdToFightCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(?string $name = null, EntityManagerInterface $em)
    {
        parent::__construct($name);

        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:insert_you_tube_id_to_fight_command')
            ->setDescription('Hello PhpStorm');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $ytIds = [
'YdXZ4j5RFqk',
'Njv9ux88OyE',
'GuENNDEueeg',
'Q1BJp0ooYYc',
'2fO_BlihomU',
'VXroaBh2THo',
'36omVtIpIXI',
'_djneJPsm9c',
'2wfxh23Rlmg',
'-Sv_6eW0uNI',
'kdvmd50N_do',
'a6rO3kWD3GA',
'1MXI70Wk2Oc',
'A4oS46HF_WA',
'1f74HuFdcPE',
'p3QSjw6NrA4',
'1GZjZwvlUow',
'Fy_G38_j494',
'uWe2UGUSlMM',
'9s5p_1SOsOI',
'2nHu6cH5eUE',
'_dDoYN99nDw',
'7wal1UGmk9s',
'FNn-YxJZAoM',
'230P8lggFzw',
'PPykjP2jCDA',
'_8AAfQx_EAk',
'xGzGck90pX0',
'w7C7dAC2auA',
'u6kK3Ju6KzY',
'CI9b6Ar58KE',
'iyRBWmk6zPo',
'-mWp62tsbXo',
'GZ2PJHoYkj4',
'-LxLHds4Gic',
'Z2jS7P1WPuI',
'nygFH-d7Yhw',
'SNkS0O5tu0k',
'KAk-fOr8HqM',
'E5oZ8VNhyqY',
'hXFGEspgQCM',
'RPmg4crlHK4',
'GIbC3f_mP80',
'qx1IvFcqags',
'gBBvahQV_X4',
'Lgov0hHnLUY',
'ODkWDD6E5RM',
        ];

        $tournament = $this->em->getRepository(Tournament::class)->find(5);

        $fights = $this->em->getRepository(Fight::class)
            ->findBy(['tournament' => $tournament], ['position' => 'ASC']);


        $i = 0;
        foreach ($fights as $fight) {
            $fight->setYoutubeId($ytIds[$i]);
            $i++;
        }

        $this->em->flush();
    }
}
