<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

//todo REFACTOR
/**
 * Ruleset
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RulesetRepository")
 */
class Ruleset
{
    /**
     * @var integer
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="string")
     */
    private $weight;


    /**
     * @ORM\Column(type="boolean")
     */
    private $male;


    /**
     * @ORM\Column(type="boolean")
     */
    private $female;


    /**
     * @ORM\Column(type="boolean")
     */
    private $junior;


    /**
     * @ORM\Column(type="boolean")
     */
    private $senior;


    /**
     * @ORM\Column(type="boolean")
     */
    private $kadet;


    /**
     * @return mixed
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param mixed $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * @return mixed
     */
    public function getMale()
    {
        return $this->male;
    }

    /**
     * @param mixed $male
     */
    public function setMale($male)
    {
        $this->male = $male;
    }

    /**
     * @return mixed
     */
    public function getFemale()
    {
        return $this->female;
    }

    /**
     * @param mixed $female
     */
    public function setFemale($female)
    {
        $this->female = $female;
    }

    /**
     * @return mixed
     */
    public function getJunior()
    {
        return $this->junior;
    }

    /**
     * @param mixed $junior
     */
    public function setJunior($junior)
    {
        $this->junior = $junior;
    }

    /**
     * @return mixed
     */
    public function getSenior()
    {
        return $this->senior;
    }

    /**
     * @param mixed $senior
     */
    public function setSenior($senior)
    {
        $this->senior = $senior;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getKadet()
    {
        return $this->kadet;
    }

    /**
     * @param mixed $kadet
     */
    public function setKadet($kadet): void
    {
        $this->kadet = $kadet;
    }
}
