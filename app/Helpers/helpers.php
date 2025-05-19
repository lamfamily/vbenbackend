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

if (! function_exists('j5_trans')) {
    /**
     * 从JSON5文件中翻译给定的消息
     *
     * @param  string  $key
     * @param  array  $replace
     * @param  string|null  $locale
     * @return string
     */
    function j5_trans($key, array $replace = [], $locale = null)
    {
        return app('json5.translator')->translate($key, $replace, $locale);
    }
}

if (! function_exists('j5_trans_choice')) {
    /**
     * 从JSON5文件中翻译给定的多元消息
     *
     * @param  string  $key
     * @param  int|array  $number
     * @param  array  $replace
     * @param  string|null  $locale
     * @return string
     */
    function j5_trans_choice($key, $number, array $replace = [], $locale = null)
    {
        $line = app('json5.translator')->translate($key, $replace, $locale);

        $segments = preg_split('/\|/', $line);

        if (is_array($number)) {
            $number = count($number);
        }

        $replace['count'] = $number;

        if (count($segments) === 2) {
            // 简单多元: 单数|复数
            return app('json5.translator')->translate(
                $segments[$number > 1 ? 1 : 0],
                $replace,
                $locale
            );
        }

        // 或者我们可以简单地返回原始翻译
        return $line;
    }
}
