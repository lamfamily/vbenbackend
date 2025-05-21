<?php

namespace Modules\Leguo\App\Http\Controllers;

use App\Enums\APICodeEnum;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Modules\Leguo\App\Models\Partner;
use Modules\Leguo\App\resources\PartnerResource;

class PartnerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('permission:Leguo:Partner:List');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $p = $request->input('p', 1);
        $ps = $request->input('ps', 10);

        $list = Partner::with('logo')->orderBy('id', 'asc')->paginate($ps, ['*'], 'page', $p);

        return api_res(APICodeEnum::SUCCESS, j5_trans('获取列表成功'), [
            'items' => PartnerResource::collection($list),
            'total' => $list->total(),
            'page' => $list->currentPage(),
            'lastPage' => $list->lastPage(),
            'pageSize' => $list->perPage(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $all_data = $request->all();
        $validator = validator($all_data, [
            'name' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'desc' => 'nullable|string|max:500',
            'status' => 'nullable|integer|in:0,1',
            'logo' => 'integer|exists:leguo.images,id',
        ]);

        if ($validator->fails()) {
            return api_res(APICodeEnum::EXCEPTION, j5_trans('参数错误'), [
                'errors' => $validator->errors()
            ]);
        }

        DB::connection('leguo')->beginTransaction();

        try {
            $partner = Partner::create(collect($all_data)->except(['logo'])->toArray());

            if ($partner) {
                // 关联logo
                if ($request->has('logo')) {
                    $partner->logo()->sync([
                        $request->logo => ['type' => 'logo', 'sort_order' => 0]
                    ]);
                }

                DB::connection('leguo')->commit();

                $partner->load('logo');

                return api_res(APICodeEnum::SUCCESS, j5_trans('创建成功'), PartnerResource::make($partner));
            } else {
                DB::connection('leguo')->rollBack();

                return api_res(APICodeEnum::EXCEPTION, j5_trans('创建失败'));
            }
        } catch (\Exception $e) {
            DB::connection('leguo')->rollBack();
            return api_res(APICodeEnum::EXCEPTION, $e->getMessage());
        }
    }

    /**
     * Show the specified resource.
     */
    public function show(Request $request, Partner $partner): JsonResponse
    {
        $partner->load('logo');

        return api_res(APICodeEnum::SUCCESS, j5_trans('获取成功'), PartnerResource::make($partner));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Partner $partner): JsonResponse
    {
        $all_data = $request->all();
        $validator = validator($all_data, [
            'name' => 'string|max:255',
            'url' => 'nullable|string|max:255',
            'desc' => 'nullable|string|max:500',
            'status' => 'nullable|integer|in:0,1',
            'logo' => 'integer|exists:leguo.images,id',
        ]);

        if ($validator->fails()) {
            return api_res(APICodeEnum::EXCEPTION, j5_trans('参数错误'), [
                'errors' => $validator->errors()
            ]);
        }

        DB::connection('leguo')->beginTransaction();

        try {
            $partner->update(collect($all_data)->except(['logo'])->toArray());

            if ($partner) {
                // 关联logo
                if ($request->has('logo')) {
                    $partner->logo()->sync([
                        $request->logo => ['type' => 'logo', 'sort_order' => 0]
                    ]);
                }

                DB::connection('leguo')->commit();

                $partner->load('logo');

                return api_res(APICodeEnum::SUCCESS, j5_trans('更新成功'), PartnerResource::make($partner));
            } else {
                DB::connection('leguo')->rollBack();

                return api_res(APICodeEnum::EXCEPTION, j5_trans('更新失败'));
            }
        } catch (\Exception $e) {
            DB::connection('leguo')->rollBack();
            return api_res(APICodeEnum::EXCEPTION, $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Partner $partner): JsonResponse
    {
        DB::connection('leguo')->beginTransaction();

        try {
            $ret = $partner->delete();

            if ($ret) {
                // 删除logo
                $partner->logo()->detach();

                DB::connection('leguo')->commit();

                return api_res(APICodeEnum::SUCCESS, j5_trans('删除成功'));
            } else {
                DB::connection('leguo')->rollBack();
                return api_res(APICodeEnum::EXCEPTION, j5_trans('删除失败'));
            }

        } catch (\Exception $e) {
            DB::connection('leguo')->rollBack();
            return api_res(APICodeEnum::EXCEPTION, $e->getMessage());
        }

    }
}
