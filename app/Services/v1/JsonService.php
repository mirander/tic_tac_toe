<?php

namespace App\Services\v1;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

/**
 * Class JsonService
 * @package App\Services\v1
 */
class JsonService
{
    /**
     * @param array $data
     * @param string $message
     * @param int $code
     * @param int $status
     * @return Application|ResponseFactory|Response
     */
    public function response(array $data, string $message, int $code = 200, int $status = 200): Response
    {
        return response([
            'data' => $data,
            'message' => $message,
            'code' => $code
        ], $status);
    }
}
