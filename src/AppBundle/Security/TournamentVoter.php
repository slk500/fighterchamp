<?php

declare(strict_types=1);

namespace AppBundle\Security;

use AppBundle\Entity\Tournament;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class TournamentVoter extends Voter
{
    const CAN_TOURNAMENT_ACTON = 'CAN_TOURNAMENT_ACTON';

    private $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::CAN_TOURNAMENT_ACTON])) {
            return false;
        }

        if (!$subject instanceof Tournament) {
            return false;
        }
        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        if ($this->decisionManager->decide($token, ['ROLE_USER']) && $token->getUser()->getType() != 3) {
            return true;
        }
        return false;
    }
}
