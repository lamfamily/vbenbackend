<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TestService
{
    public static function getTestMenus()
    {
        $url = 'https://mock-napi.vben.pro/api/system/menu/list';
        $res = Http::withHeaders(
            [
                'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6MCwicGFzc3dvcmQiOiIxMjM0NTYiLCJyZWFsTmFtZSI6IlZiZW4iLCJyb2xlcyI6WyJzdXBlciJdLCJ1c2VybmFtZSI6InZiZW4iLCJpYXQiOjE3NDQwODYyMDQsImV4cCI6MTc0NDY5MTAwNH0.6hkTKBlbj3fsn6LwQlG5YgTyUqObm779B7cOju2DISo',
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ]
        )->get($url);

        return $res->json();
    }
}
