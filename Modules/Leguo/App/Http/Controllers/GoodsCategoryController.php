<?php

namespace Modules\Leguo\App\Http\Controllers;

use App\Enums\APICodeEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Leguo\App\Models\Goods;
use Modules\Leguo\App\Models\GoodsCategory;

class GoodsCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('permission:Leguo:GoodsCategory:List');
    }

    public function index(Request $request): JsonResponse
    {
        $p = $request->input('p', 1);
        $ps = $request->input('ps', 10);
        $goods_category_list = GoodsCategory::orderBy('id', 'asc')->paginate($ps, ['*'], 'page', $p);

        return api_res(APICodeEnum::SUCCESS, j5_trans('获取列表成功'), [
            'list' => $goods_category_list->items(),
            'total' => $goods_category_list->total(),
            'current_page' => $goods_category_list->currentPage(),
            'last_page' => $goods_category_list->lastPage(),
            'per_page' => $goods_category_list->perPage(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $all_data = $request->all();
        $validator = validator($all_data, [
            'name' => 'required|string|max:255',
            'desc' => 'nullable|string|max:500',
            'status' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return api_res(APICodeEnum::EXCEPTION, __('参数错误'), [
                'errors' => $validator->errors()
            ]);
        }

        // 校验name 是否存在
        $exists = GoodsCategory::where('name', $request->name)->exists();
        if ($exists) {
            return api_res(APICodeEnum::EXCEPTION, __('数据已存在'));
        }

        // 创建商品分类
        $goods_category = GoodsCategory::create($all_data);
        if ($goods_category) {
            return api_res(APICodeEnum::SUCCESS, __('创建成功'), GoodsCategory::find($goods_category->id));
        } else {
            return api_res(APICodeEnum::EXCEPTION, __('创建失败'));
        }
    }


    public function update(Request $request, GoodsCategory $goods_category): JsonResponse
    {
        $all_data = $request->all();

        $validator = validator($all_data, [
            'name' => 'required|string|max:255',
            'desc' => 'nullable|string|max:500',
            'status' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return api_res(APICodeEnum::EXCEPTION, __('参数错误'), [
                'errors' => $validator->errors()
            ]);
        }

        // 校验name 是否存在
        $exists = GoodsCategory::where('name', $request->name)->where('id', '<>', $goods_category->id)->exists();
        if ($exists) {
            return api_res(APICodeEnum::EXCEPTION, __('数据已存在'));
        }

        // 更新商品分类
        if ($goods_category->update($all_data)) {
            return api_res(APICodeEnum::SUCCESS, __('更新成功'), GoodsCategory::find($goods_category->id));
        } else {
            return api_res(APICodeEnum::EXCEPTION, __('更新失败'));
        }
    }

    public function destroy(Request $request, GoodsCategory $goods_category): JsonResponse
    {
        // 检查是否有商品
        if ($goods_category->goods()->exists()) {
            return api_res(APICodeEnum::EXCEPTION, __('已关联商品，无法删除'));
        }

        // 删除商品分类
        if ($goods_category->delete()) {
            return api_res(APICodeEnum::SUCCESS, __('删除成功'), GoodsCategory::find($goods_category->id));
        } else {
            return api_res(APICodeEnum::EXCEPTION, __('删除失败'));
        }
    }

}
