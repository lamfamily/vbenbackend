<?php

return [

    /*
    |--------------------------------------------------------------------------
    | 驗證語言行
    |--------------------------------------------------------------------------
    |
    | 以下語言行包含驗證器類使用的默認錯誤消息。某些規則有多個版本，
    | 例如大小規則。您可以根據需要自由調整這些消息。
    |
    */

    'accepted' => ':attribute 必須被接受。',
    'accepted_if' => '當 :other 為 :value 時，:attribute 必須被接受。',
    'active_url' => ':attribute 必須是有效的網址。',
    'after' => ':attribute 必須是 :date 之後的日期。',
    'after_or_equal' => ':attribute 必須是 :date 當天或之後的日期。',
    'alpha' => ':attribute 只能包含字母。',
    'alpha_dash' => ':attribute 只能包含字母、數字、破折號和下劃線。',
    'alpha_num' => ':attribute 只能包含字母和數字。',
    'array' => ':attribute 必須是數組。',
    'ascii' => ':attribute 只能包含單字節字母數字字符和符號。',
    'before' => ':attribute 必須是 :date 之前的日期。',
    'before_or_equal' => ':attribute 必須是 :date 當天或之前的日期。',
    'between' => [
        'array' => ':attribute 必須有 :min 到 :max 個項目。',
        'file' => ':attribute 必須在 :min 到 :max KB 之間。',
        'numeric' => ':attribute 必須在 :min 到 :max 之間。',
        'string' => ':attribute 必須在 :min 到 :max 個字符之間。',
    ],
    'boolean' => ':attribute 必須為布爾值。',
    'can' => ':attribute 包含未授權的值。',
    'confirmed' => ':attribute 確認不匹配。',
    'current_password' => '密碼不正確。',
    'date' => ':attribute 必須是有效日期。',
    'date_equals' => ':attribute 必須等於 :date。',
    'date_format' => ':attribute 必須符合格式 :format。',
    'decimal' => ':attribute 必須有 :decimal 位小數。',
    'declined' => ':attribute 必須被拒絕。',
    'declined_if' => '當 :other 為 :value 時，:attribute 必須被拒絕。',
    'different' => ':attribute 和 :other 必須不同。',
    'digits' => ':attribute 必須是 :digits 位數字。',
    'digits_between' => ':attribute 必須在 :min 和 :max 位數字之間。',
    'dimensions' => ':attribute 圖片尺寸無效。',
    'distinct' => ':attribute 有重複值。',
    'doesnt_end_with' => ':attribute 不能以以下之一結尾: :values。',
    'doesnt_start_with' => ':attribute 不能以以下之一開頭: :values。',
    'email' => ':attribute 必須是有效的郵箱地址。',
    'ends_with' => ':attribute 必須以以下之一結尾: :values。',
    'enum' => '所選 :attribute 無效。',
    'exists' => '所選 :attribute 無效。',
    'extensions' => ':attribute 必須有以下擴展名之一: :values。',
    'file' => ':attribute 必須是文件。',
    'filled' => ':attribute 不能為空。',
    'gt' => [
        'array' => ':attribute 必須多於 :value 個項目。',
        'file' => ':attribute 必須大於 :value KB。',
        'numeric' => ':attribute 必須大於 :value。',
        'string' => ':attribute 必須多於 :value 個字符。',
    ],
    'gte' => [
        'array' => ':attribute 必須有 :value 個或更多項目。',
        'file' => ':attribute 必須大於或等於 :value KB。',
        'numeric' => ':attribute 必須大於或等於 :value。',
        'string' => ':attribute 必須大於或等於 :value 個字符。',
    ],
    'hex_color' => ':attribute 必須是有效的十六進制顏色。',
    'image' => ':attribute 必須是圖片。',
    'in' => '所選 :attribute 無效。',
    'in_array' => ':attribute 必須存在於 :other 中。',
    'integer' => ':attribute 必須是整數。',
    'ip' => ':attribute 必須是有效的 IP 地址。',
    'ipv4' => ':attribute 必須是有效的 IPv4 地址。',
    'ipv6' => ':attribute 必須是有效的 IPv6 地址。',
    'json' => ':attribute 必須是有效的 JSON 字符串。',
    'lowercase' => ':attribute 必須為小寫。',
    'lt' => [
        'array' => ':attribute 必須少於 :value 個項目。',
        'file' => ':attribute 必須小於 :value KB。',
        'numeric' => ':attribute 必須小於 :value。',
        'string' => ':attribute 必須少於 :value 個字符。',
    ],
    'lte' => [
        'array' => ':attribute 不能多於 :value 個項目。',
        'file' => ':attribute 必須小於或等於 :value KB。',
        'numeric' => ':attribute 必須小於或等於 :value。',
        'string' => ':attribute 必須小於或等於 :value 個字符。',
    ],
    'mac_address' => ':attribute 必須是有效的 MAC 地址。',
    'max' => [
        'array' => ':attribute 不能多於 :max 個項目。',
        'file' => ':attribute 不能大於 :max KB。',
        'numeric' => ':attribute 不能大於 :max。',
        'string' => ':attribute 不能多於 :max 個字符。',
    ],
    'max_digits' => ':attribute 不能多於 :max 位數字。',
    'mimes' => ':attribute 必須是 :values 類型的文件。',
    'mimetypes' => ':attribute 必須是 :values 類型的文件。',
    'min' => [
        'array' => ':attribute 至少有 :min 個項目。',
        'file' => ':attribute 至少為 :min KB。',
        'numeric' => ':attribute 至少為 :min。',
        'string' => ':attribute 至少為 :min 個字符。',
    ],
    'min_digits' => ':attribute 至少有 :min 位數字。',
    'missing' => ':attribute 必須缺失。',
    'missing_if' => '當 :other 為 :value 時，:attribute 必須缺失。',
    'missing_unless' => '除非 :other 為 :value，否則 :attribute 必須缺失。',
    'missing_with' => '當 :values 存在時，:attribute 必須缺失。',
    'missing_with_all' => '當 :values 都存在時，:attribute 必須缺失。',
    'multiple_of' => ':attribute 必須是 :value 的倍數。',
    'not_in' => '所選 :attribute 無效。',
    'not_regex' => ':attribute 格式無效。',
    'numeric' => ':attribute 必須是數字。',
    'password' => [
        'letters' => ':attribute 必須包含至少一個字母。',
        'mixed' => ':attribute 必須包含至少一個大寫字母和一個小寫字母。',
        'numbers' => ':attribute 必須包含至少一個數字。',
        'symbols' => ':attribute 必須包含至少一個符號。',
        'uncompromised' => '給定的 :attribute 已出現在數據洩露中，請更換 :attribute。',
    ],
    'present' => ':attribute 必須存在。',
    'present_if' => '當 :other 為 :value 時，:attribute 必須存在。',
    'present_unless' => '除非 :other 為 :value，否則 :attribute 必須存在。',
    'present_with' => '當 :values 存在時，:attribute 必須存在。',
    'present_with_all' => '當 :values 都存在時，:attribute 必須存在。',
    'prohibited' => ':attribute 被禁止。',
    'prohibited_if' => '當 :other 為 :value 時，:attribute 被禁止。',
    'prohibited_unless' => '除非 :other 在 :values 中，否則 :attribute 被禁止。',
    'prohibits' => ':attribute 禁止 :other 存在。',
    'regex' => ':attribute 格式無效。',
    'required' => ':attribute 為必填項。',
    'required_array_keys' => ':attribute 必須包含以下條目: :values。',
    'required_if' => '當 :other 為 :value 時，:attribute 為必填項。',
    'required_if_accepted' => '當 :other 被接受時，:attribute 為必填項。',
    'required_unless' => '除非 :other 在 :values 中，否則 :attribute 為必填項。',
    'required_with' => '當 :values 存在時，:attribute 為必填項。',
    'required_with_all' => '當 :values 都存在時，:attribute 為必填項。',
    'required_without' => '當 :values 不存在時，:attribute 為必填項。',
    'required_without_all' => '當 :values 都不存在時，:attribute 為必填項。',
    'same' => ':attribute 和 :other 必須一致。',
    'size' => [
        'array' => ':attribute 必須包含 :size 個項目。',
        'file' => ':attribute 必須為 :size KB。',
        'numeric' => ':attribute 必須為 :size。',
        'string' => ':attribute 必須為 :size 個字符。',
    ],
    'starts_with' => ':attribute 必須以以下之一開頭: :values。',
    'string' => ':attribute 必須是字符串。',
    'timezone' => ':attribute 必須是有效的時區。',
    'unique' => ':attribute 已被佔用。',
    'uploaded' => ':attribute 上傳失敗。',
    'uppercase' => ':attribute 必須為大寫。',
    'url' => ':attribute 必須是有效的網址。',
    'ulid' => ':attribute 必須是有效的 ULID。',
    'uuid' => ':attribute 必須是有效的 UUID。',

    /*
    |--------------------------------------------------------------------------
    | 自定義驗證語言行
    |--------------------------------------------------------------------------
    |
    | 您可以使用“attribute.rule”的約定為屬性指定自定義驗證消息。
    | 這使您可以為給定的屬性規則快速指定特定的自定義語言行。
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => '自定義消息',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | 自定義驗證屬性
    |--------------------------------------------------------------------------
    |
    | 以下語言行用於將我們的屬性佔位符替換為更易讀的內容，
    | 例如將“email”替換為“郵箱地址”。這有助於使消息更具表現力。
    |
    */

    'attributes' => [],

];
