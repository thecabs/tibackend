<?php


use Symfony\Component\HttpFoundation\JsonResponse;
function apiResponse($data, $message = '', $statusCode = 200, $meta = ''): JsonResponse
{
    $message = (is_array($message)) ? reset($message) : $message;

    $response['data'] =  ($data) ?? [];
    $response['message'] = (is_array($message)) ? $message[0] : $message;

    if (!empty($meta)) {
        $response = array_merge($response, $meta);
    }

    return response()->json($response, $statusCode);
}
