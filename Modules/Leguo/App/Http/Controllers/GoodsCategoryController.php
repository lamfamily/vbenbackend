<?php

namespace Modules\Leguo\App\Http\Controllers;

use App\Enums\APICodeEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Leguo\App\Models\GoodsCategory;
use Modules\Leguo\App\resources\GoodsCategoryResource;

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
        $list = GoodsCategory::orderBy('id', 'asc')->paginate($ps, ['*'], 'page', $p);

        return api_res(APICodeEnum::SUCCESS, j5_trans('获取列表成功'), [
            'items' => GoodsCategoryResource::collection($list),
            'total' => $list->total(),
            'page' => $list->currentPage(),
            'lastPage' => $list->lastPage(),
            'pageSize' => $list->perPage(),
        ]);
    }

    public function show(Request $request, GoodsCategory $goods_category): JsonResponse
    {
        return api_res(APICodeEnum::SUCCESS, j5_trans('获取成功'), GoodsCategoryResource::make($goods_category));
    }

    public function store(Request $request): JsonResponse
    {
        $all_data = $request->all();
        $validator = validator($all_data, [
            // 'name' => 'required|string|max:255',
            'name' => 'required|string|max:255|unique:leguo.goods_category,name',
            'desc' => 'nullable|string|max:500',
            'status' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return api_res(APICodeEnum::EXCEPTION, j5_trans('参数错误'), [
                'errors' => $validator->errors()
            ]);
        }

        // 校验name 是否存在
        // $exists = GoodsCategory::where('name', $request->name)->exists();
        // if ($exists) {
        //     return api_res(APICodeEnum::EXCEPTION, j5_trans('数据已存在'));
        // }

        // 创建商品分类
        $goods_category = GoodsCategory::create($all_data);
        if ($goods_category) {
            return api_res(APICodeEnum::SUCCESS, j5_trans('创建成功'), GoodsCategoryResource::make($goods_category));
        } else {
            return api_res(APICodeEnum::EXCEPTION, j5_trans('创建失败'));
        }
    }


    public function update(Request $request, GoodsCategory $goods_category): JsonResponse
    {
        $all_data = $request->all();

        $validator = validator($all_data, [
            // unique:goods_categories,name,{$goods_category->id}
                // 1. 字段在 goods_categories 表的 name 字段中必须是唯一的，
                // 2. 但会忽略掉主键为 $goods_category->id 的那一行（通常用于“编辑”时，自己和自己比不算冲突）。
            'name' => 'nullable|string|max:255|unique:leguo.goods_category,name,' . $goods_category->id,
            'desc' => 'nullable|string|max:500',
            'status' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return api_res(APICodeEnum::EXCEPTION, j5_trans('参数错误'), [
                'errors' => $validator->errors()
            ]);
        }

        // 校验name 是否存在
        // $exists = GoodsCategory::where('name', $request->name)->where('id', '<>', $goods_category->id)->exists();
        // if ($exists) {
        //     return api_res(APICodeEnum::EXCEPTION, j5_trans('数据已存在'));
        // }

        // 更新商品分类
        if ($goods_category->update($all_data)) {
            return api_res(APICodeEnum::SUCCESS, j5_trans('更新成功'), GoodsCategoryResource::make($goods_category));
        } else {
            return api_res(APICodeEnum::EXCEPTION, j5_trans('更新失败'));
        }
    }

    public function destroy(Request $request, GoodsCategory $goods_category): JsonResponse
    {
        // 检查是否有商品
        if ($goods_category->goods()->exists()) {
            return api_res(APICodeEnum::EXCEPTION, j5_trans('已关联商品，无法删除'));
        }

        // 删除商品分类
        if ($goods_category->delete()) {
            return api_res(APICodeEnum::SUCCESS, j5_trans('删除成功'), GoodsCategoryResource::make($goods_category));
        } else {
            return api_res(APICodeEnum::EXCEPTION, j5_trans('删除失败'));
        }
    }

}
