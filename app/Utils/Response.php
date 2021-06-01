<?php

namespace App\Utils;

class Response
{
    public static function HttpResponse(int $httpCode, $data, string $message, bool $isError)
    {
        $response = [
            'data' => $data,
            'message' => $message,
            'error' => $isError
        ];
        return response($response, $httpCode);
    }
}
