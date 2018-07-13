<?php

define('CLI_ROOT_DIR', __DIR__);

require_once __DIR__ . '/vendor/autoload.php';
chdir(__DIR__);

$dot = new \Dotenv\Dotenv(__DIR__);
$dot->safeLoad();
$app = new Silly\Application('CloudDK CLI - Interactive with cloud.dk api', 0.1);

if($_ENV['API_KEY'] === null){
    echo 'API_KEY need to be specified'.PHP_EOL;
    exit(0);
}

$container = new \DI\Container();
$container->set('client', function (){
    return new \GuzzleHttp\Client([
        'base_uri' => 'https://api.cloud.dk/v1/',
        'timeout'  => 60.0,
        'headers' => [
            'X-Api-Key' => $_ENV['API_KEY']
        ]
    ]);
});
$app->addCommands([
    new \Sazo\CloudDk\Commands\ListServer(null, $container),
    new \Sazo\CloudDk\Commands\CreateServer(null, $container),
    new \Sazo\CloudDk\Commands\DeleteServer(null, $container),
    new \Sazo\CloudDk\Commands\SecureServer(null, $container),
    new \Sazo\CloudDk\Commands\RootPassword(null, $container)
]);
$app->useContainer($container);
$app->run();
