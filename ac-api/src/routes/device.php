<?php

use Slim\Http\Request;
use Slim\Http\Response;



// Routes
$app->get('/swagger-ui/api', function ($request, $response, $args) {
    $openapi = OpenApi\scan('/var/www/src/routes');
    header('Content-Type: application/html');
    echo $openapi->toYaml();

});

/**
 * @OA\Info(title="Acme API", version="1")
 */


/**
 * api for device
 *
 * @return Response
 *
 * @OA\Get(
 *     path="/api/v1/devices",
 *     description="Returns all devices.",
 *     @OA\Parameter(
 *         description="filter example is {isActive:false} ",
 *         in="query",
 *         name="filter",
 *         required=false,
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
 *     @OA\Parameter(
 *         description="sort  as ['title','ASC'] ",
 *         in="query",
 *         name="sort",
 *         required=false,
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
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
$app->get('/api/v1/devices', function (Request $request, Response $response, array $args) {
    $this->logger->info("Cb-Ac '/api/v1/devices' route");

    $deviceService = new \service\DeviceService();


    //http://104.248.180.30/api/v1/devices?filter=%7B%7D&range=%5B0%2C9%5D&sort=%5B%22id%22%2C%22DESC%22%5D

    $rangeArray = json_decode($request->getQueryParam("range"));


    $filter = $request->getQueryParam("filter");


    if (!empty($filter)) {
        $filter = str_replace("{", "", $filter);
        $filter = str_replace("}", "", $filter);
        $filter = str_replace("\"", "", $filter);
        if (!empty($filter)) {
            $filter = explode(":", $filter);
        }
    } else {
        $filter = null;
    }

    $range = str_replace("[", "", $request->getQueryParam("range"));
    $range = str_replace("]", "", $range);

    $range = str_replace(",", "-", $range);


    $devices = $deviceService->getDevicesDb(null, $rangeArray, $filter);

    $range = $range . "/" . sizeof($devices);

    $newResponse = $response->withHeader('Content-Range', $range);


    $body = $newResponse->getBody();

    $body->write(json_encode($devices));

    return $newResponse;//->withJson($test, 200);
});


/**
 * get an AC unit
 *
 * @return Response
 *
 * @OA\Get(
 *     path="/api/v1/devices/{deviceId}",
 *     description="Returns all devices.",
 *     @OA\Parameter(
 *         description="device Id",
 *         in="path",
 *         name="deviceId",
 *         required=true,
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
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
$app->get('/api/v1/devices/[{deviceId}]', function (Request $request, Response $response, array $args) {
    $this->logger->info("Cb-Ac '/api/v1/devices/[{deviceId}]' route");
    $deviceService = new \service\DeviceService();
    $dataResult = $deviceService->getDevicesDb($args["deviceId"], null, null);


    return $response->withJson($dataResult, 200);


});


/**
 * get all the list of alerts
 *
 * @return Response
 *
 *
 * @OA\Get(
 *     path="/api/v1/alerts/",
 *     description="Returns all alerts for devices.",
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
$app->get('/api/v1/alerts', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Cb-Ac '/api/v1/alerts' route");

    $deviceService = new \service\DeviceService();

    $dataResult = $deviceService->getAlerts(null, null);


    return $response->withJson($dataResult, 200);
});


/**
 * get all the list of measurements for given AC Unit
 *
 * @return Response
 *
 *
 * @OA\Get(
 *     path="/api/v1/device/measurement/list/{serialNumber}",
 *     description="Returns all devices.",
 *     @OA\Parameter(
 *         description="serial number",
 *         in="path",
 *         name="serialNumber",
 *         required=true,
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
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
    $deviceService = new \service\DeviceService();

    $dataResult = $deviceService->getMeasurements($args["serialNumber"]);


    return $response->withJson($dataResult, 200);
});


/**
 * add measurements
 *
 * @return Response
 *
 * @OA\Post(
 *     path="/api/v1/device/measurement/add",
 *     description="add upto 500 measurements",
 *    @OA\RequestBody(
 *       required=true,
 *       description="add measurements",
 *       @OA\MediaType(
 *           mediaType="application/json",
 *       )
 *
 *   ),
 *     @OA\Response(
 *         response=200,
 *         description="OK"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="serial number is unique",
 *     )
 * )
 */
$app->post('/api/v1/device/measurement/add', function (Request $request, Response $response, array $args) {

    $userId = $request->getHeader('UserId');
    $transactionId = $request->getHeader('transactionId');

    $record = $request->getParsedBody();
    $deviceService = new \service\DeviceService();
    $result = $deviceService->addMeasurements($record);

    if ($result == "success") {
        return $response->withJson($record, 200);
    } else {
        $error = array("error" => $result);
        return $response->withJson($error, 400);
    }
});


$app->get('/api/test', function (Request $request, Response $response, array $args) {
    $rangeArray = json_decode($request->getQueryParam("range"));


    $filter = $request->getQueryParam("filter");


    if (!empty($filter)) {
        $filter = str_replace("{", "", $filter);
        $filter = str_replace("}", "", $filter);
        $filter = str_replace("\"", "", $filter);
        if (!empty($filter)) {
            $filter = explode(":", $filter);
        }
    } else {
        $filter = null;
    }

    $range = str_replace("[", "", $request->getQueryParam("range"));
    $range = str_replace("]", "", $range);

    $range = str_replace(",", "-", $range);

    $deviceService = new \service\DeviceService();
    return $response->withJson($deviceService->getDevicesMock(null, $rangeArray, $filter), 200);

});

/**
 * Register a device
 *
 * @return Response
 *
 * @OA\Post(
 *     path="/api/v1/device/register",
 *     description="register a device",
 *    @OA\RequestBody(
 *       required=true,
 *       description="register a device",
 *       @OA\MediaType(
 *           mediaType="application/json",
 *       )
 *
 *   ),
 *     @OA\Response(
 *         response=200,
 *         description="OK"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="serial number is unique",
 *     )
 * )
 */
$app->post('/api/v1/device/register', function (Request $request, Response $response, array $args) {

    $userId = $request->getHeader('UserId');
    $transactionId = $request->getHeader('transactionId');

    $this->logger->info("Cb-Ac '/api/v1/device/register' route" . "::".$transactionId);


    $record = $request->getParsedBody();
    $deviceService = new \service\DeviceService();
    $result = $deviceService->register($record);

    if ($result == "success") {
        return $response->withJson($record, 200);
    } else {
        $error = array("error" => $result);
        return $response->withJson($error, 400);
    }


});