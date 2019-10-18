<?php
/**
 * Created by PhpStorm.
 * User: slk
 * Date: 12/31/17
 * Time: 12:46 PM
 */

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

//class not used

/**
 * @ORM\Entity
 * @ORM\Table(name="user_insurance_data")
 */
class UserInsuranceData
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string", nullable=true, unique=true)
     * @Assert\Length(min="11", max="11")
     * @var string
     */
    private $pesel;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    private $fatherName;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    private $motherName;

//    /**
//     * @ORM\OneToOne(targetEntity="AppBundle\Entity\User")
//     */
//    private $user;

    public function getPesel(): string
    {
        return $this->pesel;
    }

    public function setPesel(string $pesel): void
    {
        $this->pesel = $pesel;
    }

    public function getFatherName(): string
    {
        return $this->fatherName;
    }

    public function setFatherName(string $fatherName): void
    {
        $this->fatherName = $fatherName;
    }

    public function getMotherName(): string
    {
        return $this->motherName;
    }

    public function setMotherName(string $motherName): void
    {
        $this->motherName = $motherName;
    }



//    /**
//     * @Assert\Callback
//     */
//    public function validate(ExecutionContextInterface $context, $payload)
//    {
//        if (!($this->motherName || $this->fatherName)) {
//            $context->buildViolation('Podaj imię Ojca albo Matki')
//                ->atPath('fatherName')
//                ->addViolation();
//        }
//    }
}
