<?php

use Symfony\Component\HttpFoundation\Request;

require_once __DIR__ . '/vendor/autoload.php';

$app = new \Silex\Application();
$app['debug'] = true;
$app['application'] = new \SpeechToText\Application();

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/views',
));

$app->get('/', function () use ($app) {

    return $app['twig']->render('demo.twig', array(
        'name' => 'leo',
    ));

});

$app->post('/process', function (Request $request) use ($app) {

    $data = json_decode($request->getContent(), true);

    $application = $app['application'];

    return $app->json(
        $application->run($data['content'], $data['sampleRate'])
    );

});

$app->run();