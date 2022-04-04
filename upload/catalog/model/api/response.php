<?php
class ModelApiResponse extends Model
{
    public static function badRequest($response)
    {
        http_response_code(404);
        $response->addHeader('Content-Type: application/json');
        $response->output();
        die();
    }

    public static function success($response, $data = null)
    {
        http_response_code(200);
        $response->addHeader('Content-Type: application/json');
        if ($data) {
            $response->setOutput(json_encode($data));
        }
        $response->output();
        die();
    }
}
