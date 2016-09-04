<?php

require_once __DIR__ . '/vendor/autoload.php';

use Silex\Application;
use SpeechToText\ApplicationService;
use Symfony\Component\HttpFoundation\Request;

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$app = new Application();
$app['debug'] = true;
$app['application'] = new ApplicationService();

$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/views',
));

$app->get('/', function () use ($app) {

    return $app['twig']->render('demo.twig');

});

$app->post('/process', function (Request $request) use ($app) {

    $data = json_decode($request->getContent(), true);

    $application = $app['application'];

    return $app->json(
        $application->run($data['content'], $data['sampleRate'])
    );

});

$app->run();