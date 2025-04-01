<?php


if (!function_exists('api_res')) {
    function api_res($code, $msg, $data = null)
    {
        return response()->json([
            'code' => $code,
            'msg' => $msg,
            'data' => $data ?? [],
        ]);
    }
}


