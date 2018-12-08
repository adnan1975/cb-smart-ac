<?php

class CustomExceptionHandler
{

    public function __invoke(Request $request, Response $response, Exception $exception)
    {
        $errors['errors'] = $exception->getMessage();
        $errors['responseCode'] = 400;

        return $response
            ->withStatus(400)
            ->withJson($errors);
    }
}
