<?php

use Slim\Http\Request;
use Slim\Http\Response;
use app\DeviceService;


//static data for now

// Routes


$app->get('/swagger-ui/api', function ($request, $response, $args) {
    $openapi = OpenApi\scan('/Users/adnanrana/work/cb/cb-ac-api/ac-api/src/');
    header('Content-Type: application/html');
    echo $openapi->toYaml();

});
/**
 * @OA\Info(title="Acme API", version="1")
 */

/**
 * get all the list of AC units
 *
 * @return Response
 *
 * @OA\Get(
 *     path="api/v1/device/list",
 *     description="Returns all devices.",
 *     tags={"device,list"},
 *     @OA\Response(
 *         response=200,
 *         description="OK"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized action.",
 *     )
 * )
 */
$app->get('/api/v1/device/list', function (Request $request, Response $response, array $args) {
    $this->logger->info("Cb-Ac '/api/v1/device/list' route");
    $test = array('serialNumber' => "1234555abc", "macAddress" => "12:3:123:132", "year" => 2018, "isActive" => true, "owner" => "Hiba");
    return $response->withJson($test, 200);
});

/**
 * get all the list of measurements for given AC Unit
 *
 * @return Response
 *
 *
 * @OA\Get(
 *     path="api/v1/device/measurement/list/[{name}]",
 *     description="Returns all devices.",
 *     @OA\Response(
 *         response=200,
 *         description="OK"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized action.",
 *     )
 * )
 */
$app->get('/api/v1/device/measurement/list/[{serialNumber}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Cb-Ac '/api/v1/device/measurement/list' route");
    $test = array('serialNumber' => $args["serialNumber"], "macAddress" => "12:3:123:132", "year" => 2018, "isActive" => true, "owner" => "Hiba");
    return $response->withJson($test, 200);
});


/**
 * add measurement for an AC unit
 *
 * @return Response
 *
 * @OA\Post(
 *     path="api/v1/device/measurement/add",
 *     description="adds list of measurements.",
 *     @OA\Response(
 *         response=200,
 *         description="OK"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized action.",
 *     )
 * )
 */
$app->post('/api/v1/device/measurement/add', function (Request $request, Response $response, array $args) {

    $userId = $request->getHeader('UserId');
    $transactionId = $request->getHeader('transactionId');

    //$dbService = new DeviceService();

    $this->logger->info("Cb-Ac '/api/v1/device/measurement/add' route");
    $allPostPutVars = $request->getParsedBody();

    //$dbService->saveMeasurement($userId, $transactionId, $allPostPutVars);
    return print_r($allPostPutVars);
});
