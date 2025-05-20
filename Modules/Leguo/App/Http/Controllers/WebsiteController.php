<?php

namespace Modules\Leguo\App\Http\Controllers;

use App\Enums\APICodeEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Leguo\App\Models\Website;

class WebsiteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('permission:Leguo:Website:List');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return view('leguo::index');
        $website = Website::first();

        if (!$website) {
            Website::create([
                'title' => '乐果网站',
            ]);

            $website = Website::first();
        }

        return api_res(APICodeEnum::SUCCESS, j5_trans('获取网站信息成功'), $website);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $all_data = $request->all();

        $website = Website::first();

        if (!$website) {
            Website::create([
                'title' => '乐果网站',
            ]);

            $website = Website::first();
        }

        $website->update($all_data);

        return api_res(APICodeEnum::SUCCESS, j5_trans('更新网站信息成功'), $website);

    }
}
