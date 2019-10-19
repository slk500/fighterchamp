<?php
/**
 * Created by PhpStorm.
 * User: slk
 * Date: 4/13/18
 * Time: 1:44 PM
 */

namespace AppBundle\Event;

use AppBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class UserCreatedEvent extends Event
{
    /**
     * @var User
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
