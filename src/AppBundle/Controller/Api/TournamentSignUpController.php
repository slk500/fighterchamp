<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\SignupTournament;
use AppBundle\Entity\Tournament;
use AppBundle\Entity\User;
use AppBundle\Form\AdminSignUpTournamentType;
use AppBundle\Service\ApiFormService;
use AppBundle\Service\RulesetService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TournamentSignUpController extends Controller
{
    public function show(SignupTournament $signUp)
    {
        return $signUp;
    }

    public function create(Request $request, EntityManagerInterface $entityManager)
    {
        $data = json_decode($request->getContent(), true);

        $user = $entityManager->getReference(User::class, $data['userId']);
        $tournament = $entityManager->getReference(Tournament::class, $data['tournamentId']);

        $signupTournament = new SignupTournament($user, $tournament);
        $signupTournament->setFormula($data['formula']);
        $signupTournament->setWeight($data['weight']);
        $signupTournament->setDiscipline($data['discipline']);

        $entityManager->persist($signupTournament);
        $entityManager->flush();

        return $signupTournament;
    }

    public function list(Tournament $tournament, EntityManagerInterface $entityManager)
    {
        return $entityManager->getRepository(SignupTournament::class) //todo remove flags delete
        ->findBy(
            [
                'tournament' => $tournament,
                'deleted_at' => null,
                'deletedAtByAdmin' => null
            ]
        );
    }

    public function listNotPair(Tournament $tournament, EntityManagerInterface $entityManager)
    {
        $freeSignUpIdsRaw = $entityManager
            ->getRepository(SignupTournament::class)->findAllSignUpButNotPairYet($tournament->getId());

        $freeSignUpIds = array_map(function (array $arr) {
            return $arr['id'];
        }, $freeSignUpIdsRaw);

        return $this->getDoctrine()
            ->getRepository(SignupTournament::class)
            ->findBy(['id' => $freeSignUpIds, 'deletedAtByAdmin' => null]);
    }

    public function delete(SignupTournament $signUpTournament, EntityManagerInterface $entityManager)
    {
        $signUpTournament->delete();
        $entityManager->flush();

        return $signUpTournament;
    }

    public function update(
        SignupTournament $signUpTournament,
        Request $request,
        EntityManagerInterface $entityManager,
        ApiFormService $apiForm,
        RulesetService $rulesetService
    ) {
        $weights = $rulesetService->getWeights($entityManager, $signUpTournament->getUser());

        $form = $this->createForm(AdminSignUpTournamentType::class, $signUpTournament, ['trait_choices' => $weights]);
        $apiForm->processForm($request, $form);

        if (!$form->isValid()) {
            $apiForm->throwValidationErrorsResponse($form, $request);
        }

        $entityManager->flush();

        return $signUpTournament;
    }
}
