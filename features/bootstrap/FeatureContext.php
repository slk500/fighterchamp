<?php

use AppBundle\Entity\SignupTournament;
use AppBundle\Entity\Tournament;
use AppBundle\Entity\User;
use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\RawMinkContext;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Tests\Builder\RulesetBuilder;
use Tests\Builder\SignupTournamentBuilder;
use Tests\Builder\TournamentBuilder;
use Tests\Builder\UserBuilder;
use Tests\Database;
use Tests\DatabaseHelper;

require_once __DIR__ . '/../../vendor/phpunit/phpunit/src/Framework/Assert/Functions.php';

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends RawMinkContext implements Context
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var UserBuilder
     */
    private $userBuilder;

    /**
     * @var TournamentBuilder
     */
    private $tournamentBuilder;

    /**
     * @var SignupTournamentBuilder
     */
    private $signupTournamentBuilder;

    /**
     * @var RulesetBuilder
     */
    private $rulesetBuilder;

    /**
     * @var DatabaseHelper
     */
    private $databaseHelper;

    public function __construct()
    {
        $kernel = new AppKernel('dev', true);
        $kernel->boot();
        $this->em = $kernel->getContainer()->get('doctrine')->getManager();
        $kernel->getContainer()->get('profiler')->disable();
        $this->userBuilder = new UserBuilder();
        $this->tournamentBuilder = new TournamentBuilder();
        $this->signupTournamentBuilder = new SignupTournamentBuilder();
        $this->rulesetBuilder = new RulesetBuilder();
        $this->databaseHelper = new DatabaseHelper(new Database('dev'));
    }

    /**
     * @BeforeScenario
     */
    public function clearData()
    {
        $this->databaseHelper->truncateAllTables();
    }

    /**
     * @Then /^I wait for result$/
     */
    public function iWaitForResult()
    {
        $this->getSession()->wait(3000);
    }

    /**
     * @When I close Symfony Dev Toolbar
     */
    public function iCloseSymfonyDevToolbar()
    {
        $this->getSession()->getPage()->find('css', '.hide-button')->click();
    }

    /**
     * @Given /^there is and admin user "([^"]*)" with password "([^"]*)"$/
     */
    public function thereIsAndAdminUserWithPassword($email, $password)
    {
        $user = $this->userBuilder
            ->withName('user')
            ->withEmail($email)
            ->withPassword($password)
            ->build();

        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @Given /^there is a user "([^"]*)"$/
     */
    public function thereIsAUser($arg1)
    {
        $user = $this->userBuilder
            ->withName('user')
            ->build();

        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * Pauses the scenario until the user presses a key. Useful when debugging a scenario.
     *
     * @Then I break
     */
    public function iPutABreakpoint()
    {
        fwrite(STDOUT, "\033[s    \033[93m[Breakpoint] Press \033[1;93m[RETURN]\033[0;93m to continue...\033[0m");
        while (fgets(STDIN, 1024) == '') {
        }
        fwrite(STDOUT, "\033[u");
        return;
    }


    /**
     * @Given there is a tournament :name
     */
    public function thereIsATournament($name)
    {
        $ruleset = $this->rulesetBuilder->build();

        $tournament = $this->tournamentBuilder
            ->withName($name)
            ->build();

        $this->em->persist($ruleset);
        $this->em->persist($tournament);
        $this->em->flush();
    }

    /**
     * @Given there is a user with type :type
     */
    public function thereIsAUserWithType(int $type): void
    {
        $user = $this->userBuilder
            ->withType($type)
            ->build();

        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @Given /^I am logged in as fighter$/
     */
    public function iAmLoggedInAsFighter()
    {
        $this->thereIsAUserWithType(User::TYPE_FIGHTER);

        $this->visitPath('/login');
        $this->getSession()->getPage()->fillField('Email', UserBuilder::DEFAULT_EMAIL);
        $this->getSession()->getPage()->fillField('Hasło', UserBuilder::DEFAULT_PASSWORD);
        $this->getSession()->getPage()->pressButton('Login');
    }

    /**
     * @Given /^"([^"]*)" have signup for "([^"]*)"$/
     */
    public function haveSignupFor($userName, $tournamentName)
    {
        $user = $this->em->getRepository(User::class)
            ->findOneBy(['name' => $userName]);

        $tournament = $this->em->getRepository(Tournament::class)
            ->findOneBy(['name' => $tournamentName]);

        $signup = $this->signupTournamentBuilder
            ->withUser($user)
            ->withTournament($tournament)
            ->build();

        $this->em->persist($signup);
        $this->em->flush();
    }

    /**
     * @Given /^I am signed up for a tournament$/
     */
    public function iAmSignedUpForATournament()
    {
        $user = $this->em->getRepository(User::class)
            ->find(1);

        $tournament = $this->em->getRepository(Tournament::class)
            ->find(1);

        $signup = $this->signupTournamentBuilder
            ->withUser($user)
            ->withTournament($tournament)
            ->build();

        $this->em->persist($signup);
        $this->em->flush();
    }

    /**
     * @Then /^SignUpTournament table should be empty$/
     */
    public function signuptournamentTableShouldBeEmpty()
    {
        $result = $this->em->getRepository(SignupTournament::class)->findAll();

        TestCase::assertEmpty($result);
    }
}
