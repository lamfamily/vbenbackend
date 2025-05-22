<?php

use Modules\Leguo\App\Models\Order;

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

if (!function_exists('curl_basic')) {
    function curl_basic($method, $url, $headers = [], $body = '', $timeout = 120)
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

        if (!empty($headers)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }

        if (strtoupper($method) == 'POST') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
            if (!empty($body)) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
            }
        }

        if (strtoupper($method) == 'DELETE') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
            if (!empty($body)) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
            }
        }

        $res = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        $err = curl_error($curl);
        curl_close($curl);

        // 指定请求参数，打印信息
        if (request()->get('debug') == 'curl') {
            echo '<pre>';
            var_dump($url, $headers, $body, $err, $res);
            echo '</pre>';
            exit;
        }

        if (!empty($err)) return ['success' => false, 'msg' => $err, 'data' => $res, 'http_code' => $http_code];

        return ['success' => true, 'msg' => 'ok', 'data' => $res, 'http_code' => $http_code];
    }
}

if (!function_exists('curl_get_basic')) {

    function curl_get_basic($url, $headers = [])
    {
        return curl_basic('GET', $url, $headers);
    }
}

if (!function_exists('curl_post_basic')) {

    function curl_post_basic($url, $headers = [], $body = '', $timeout = 120)
    {
        return curl_basic('POST', $url, $headers, $body, $timeout);
    }
}

if (!function_exists('gen_order_no')) {
    function gen_leguo_order_no()
    {
        do {
            $order_no = 'O' . date('YmdHis') . rand(1000, 9999);
        } while (Order::where('order_no', $order_no)->exists());

        return $order_no;
    }
}