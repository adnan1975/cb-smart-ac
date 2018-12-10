<?php

use Slim\Http\Request;
use Slim\Http\Response;




/**
 * api for admins
 *
 * @return Response
 *
 * @OA\Get(
 *     path="/api/v1/admins",
 *     description="Returns all admins.",
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
$app->get('/api/v1/admins', function (Request $request, Response $response, array $args) {
    $this->logger->info("Cb-Ac '/api/v1/admins' route");
    $adminService = new \service\AdminService();


    //http://104.248.180.30/api/v1/admins?filter=%7B%7D&range=%5B0%2C9%5D&sort=%5B%22id%22%2C%22DESC%22%5D

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


    $admins = $adminService->getAdminsMock(null, $rangeArray, $filter);

    $range = $range . "/" . sizeof($admins);

    $newResponse = $response->withHeader('Content-Range', $range);


    $body = $newResponse->getBody();

    $body->write(json_encode($admins));

    return $newResponse;//->withJson($test, 200);
});


/**
 * get an Admin
 *
 * @return Response
 *
 * @OA\Get(
 *     path="/api/v1/admins/{adminId}",
 *     description="Returns all admins.",
 *     @OA\Parameter(
 *         description="admin id",
 *         in="path",
 *         name="adminId",
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
$app->get('/api/v1/admins/[{adminId}]', function (Request $request, Response $response, array $args) {
    $this->logger->info("Cb-Ac '/api/v1/admins/[{adminId}]' route");
    $adminService = new \service\AdminService();
    $dataResult = $adminService->getAdminsMock($args["adminId"], null, null);


    return $response->withJson($dataResult, 200);


});
