<?php

if (!function_exists('api_res')) {
    /**
     * @param int $code
     * @param string $msg
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    function api_res(int $code = 0, string $msg = '', array $data = []): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ]);
    }
}
