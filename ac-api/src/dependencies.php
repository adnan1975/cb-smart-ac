<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

//error handler
$container['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        return $response->withStatus(500)
            ->withHeader('Content-Type', 'application/json')
            ->write('{"error":"'.$exception->getMessage()."\"}");
    };
};

/*


$container['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        $message = $exception->getMessage();
        $status = 300;


        $existingkey = "Integrity constraint violation: 1062 Duplicate entry";
        if (strpos($exception->getMessage(), $existingkey) !== FALSE) {
            $status = 400;
        }

return $response->$response->withJson(array("error"=>$message), $status)
    ->write();
};
};

 */