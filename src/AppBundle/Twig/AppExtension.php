<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 08.08.16
 * Time: 12:57
 */

namespace AppBundle\Twig;

use Twig_Extension;

class AppExtension extends Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('getAge', array($this, 'getAgefilter')),
            new \Twig_SimpleFilter('hoursToMins', array($this, 'hoursToMins')),
            new \Twig_SimpleFilter('getAgeAtTournament', array($this, 'getAgeAtTournament')),
        );
    }

    public function getAgeFilter($date)
    {
        if (!$date instanceof \DateTime) {
            // turn $date into a valid \DateTime object or let return
            return null;
        }

//        $referenceDateTimeObject = new \DateTime('now');
//
//        $diff = $referenceDateTimeObject->diff($date);
//
//        return $diff->y;

        $date = $date->format('Y');

        $now = (new \DateTime('now'))->format('Y');

        return $now - $date;
    }

    public function getAgeAtTournament($birthday, $tournamentDay)
    {
        if (!$birthday || !$tournamentDay instanceof \DateTime) {
            return null;
        }

        $diff = $tournamentDay->diff($birthday);

        return $diff->y;
    }


    public function hoursToMins($time, $format = '%2d:%02d')
    {
        if ($time < 1) {
            return null;
        }
        $hours = floor($time / 60);
        $minutes = ($time % 60);
        return sprintf($format, $hours, $minutes);
    }


    public function getName()
    {
        return 'app_extension';
    }
}
