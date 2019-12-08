<?php

namespace Tests;

use Shopsys\HttpSmokeTesting\HttpSmokeTestCase;
use Shopsys\HttpSmokeTesting\RequestDataSet;
use Shopsys\HttpSmokeTesting\RouteConfig;
use Shopsys\HttpSmokeTesting\RouteConfigCustomizer;
use Shopsys\HttpSmokeTesting\RouteInfo;
use Symfony\Component\HttpFoundation\Request;

class SmokeTest extends HttpSmokeTestCase
{
    public static function setUpBeforeClass()
    {
        $databaseHelper = new DatabaseHelper(new Database('test'));
        $databaseHelper->truncateAllTables();

        $kernel = new \AppKernel("test", true);
        $kernel->boot();
        $application = new \Symfony\Bundle\FrameworkBundle\Console\Application($kernel);
        $application->setAutoExit(false);

        $options["-e"] = "test";
        $options["-q"] = null;
        $options = array_merge($options, array('command' => "doctrine:fixtures:load"));
        $application->run(new \Symfony\Component\Console\Input\ArrayInput($options));
    }


    protected function setUp()
    {
        parent::setUp();
    }

    protected function createRequest(RequestDataSet $requestDataSet)
    {
        $token = self::$kernel->getContainer()->get('lexik_jwt_authentication.encoder')->encode(['userId' => 1]);

        $uri = $this->getRouterAdapter()->generateUri($requestDataSet);

        $request = Request::create($uri);

        $request->headers->set('X-Requested-With', 'XMLHttpRequest');
        $request->headers->set('Authorization', 'Bearer ' . $token);

        $requestDataSet->getAuth()
            ->authenticateRequest($request);

        return $request;
    }


    protected function customizeRouteConfigs(RouteConfigCustomizer $routeConfigCustomizer)
    {
        $routeConfigCustomizer
            ->customize(function (RouteConfig $config, RouteInfo $info) {
                $skipRoutes = [
                    'fights_not_weighted_remove',
                    'setWinner',
                    'toggleFightReady',
                    'setDay',
                    'set_is_paid',
                    'set_weighted',
                    'admin_mail',
                    'news',
                    'news_new',
                    'admin_user_new',
                    'connect_facebook',
                    'connect_facebook_check',
                    'logout',
                    'user_edit_view',
                    'admin_api_user_list',
                    'admin_api_tournament_list',
                    'admin_tournament_fights',
                    'allFightsReady',
                    'changeOrderFight',
                    'changePositionFight', // need more parameters
                    'admin_tournament_toggle_delete_by_admin', // todo remove redirect from controller, just js reload page
                    'club_list', // need fixtures
                    'club_show', // need fixtures
                    'user_create_view'
                ];

                $postRoute = [
                    'user_create',
                    'api_user_update',
                    'setNullOnImage',
                    'api_image_upload',
                    'admin_user_list',
                    'admin_tournament_create',
                    'set_is_licence',
                    'toggle_corners',
                    'api_signup_create'
                ];

                $requireId = [
                    'admin_tournament_fights',
                    'fight_show',
                    'api_user_show',
                    'admin_tournament_pair',
                    'admin_tournament_sign_up',
                    'admin_create_signUp',
                    'api_fight_show',
                    'api_tournament_show'


                ];

                $requireType = [
                    'user_register_form_view',
                    'user_update_form_view'
                ];

                if (!$info->isHttpMethodAllowed('GET')) {
                    $config->skipRoute('Only routes supporting GET method are tested.');
                }

                // skip debug routes
                if ($info->getRouteName()[0] === '_') {
                    $config->skipRoute();
                }

                if (in_array($info->getRouteName(), $requireType)) {
                    $config->changeDefaultRequestDataSet()
                        ->setParameter('type', 1);
                }

                $config->changeDefaultRequestDataSet()
                        ->setParameter('id', 1);


                if (in_array($info->getRouteName(), $requireId)) {
                    $config->skipRoute();
                }
                if (in_array($info->getRouteName(), $skipRoutes)) {
                    $config->skipRoute();
                }
                if (in_array($info->getRouteName(), $postRoute)) {
                    $config->skipRoute();
                }
            });
    }
}
