<?php

if (!function_exists('api_res')) {
    /**
     * @param int $code
     * @param string $msg
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    function api_res(int $code = 0, string $msg = '', $data = [], int $status = 200): \Illuminate\Http\JsonResponse
    {
        $errors = $data['errors'] ?? [];
        $err_str = '';
        $err_arr = [];
        if ($errors) {
            $errors = json_decode($errors, true);
            foreach ($errors as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $v) {
                        $err_arr[] = $v;
                    }
                } else {
                    $err_arr[] = $value;
                }
            }
            $err_str = ': ' . implode(',', $err_arr);
        }

        return response()->json([
            'code' => $code,
            'message' => $msg . $err_str,
            'data' => $data,
        ], $status);
    }
}
