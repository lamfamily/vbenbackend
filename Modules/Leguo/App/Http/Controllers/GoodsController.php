<?php

namespace Modules\Leguo\App\Http\Controllers;

use App\Enums\APICodeEnum;
use App\Enums\DefaultStatusEnum;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Leguo\App\Models\Goods;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Leguo\App\resources\GoodsResource;

class GoodsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('permission:Leguo:Goods:List');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $p = $request->input('p', 1);
        $ps = $request->input('ps', 10);
        $goods_list = Goods::with('images')->orderBy('id', 'asc')->paginate($ps, ['*'], 'page', $p);

        return api_res(APICodeEnum::SUCCESS, j5_trans('获取列表成功'), [
            'items' => GoodsResource::collection($goods_list),
            'total' => $goods_list->total(),
            'page' => $goods_list->currentPage(),
            'lastPage' => $goods_list->lastPage(),
            'pageSize' => $goods_list->perPage(),
        ]);
    }


    public function show(Request $request, Goods $good): JsonResponse
    {
        $good->load('images');

        return api_res(APICodeEnum::SUCCESS, j5_trans('获取成功'), GoodsResource::make($good));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $all_data = $request->all();
        $validator = validator($all_data, [
            // 'name' => 'required|string|max:255|unique:leguo.goods,name',
            'name' => 'required|string|max:255',
            'desc' => 'nullable|string|max:500',
            'stock_num' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            // currency 必须3位
            'currency' => 'required|string|size:3',
            'status' => 'nullable|boolean',
            'image_ids' => 'array',
            'image_ids.*' => 'integer|exists:leguo.images,id',
        ]);

        if ($validator->fails()) {
            return api_res(APICodeEnum::EXCEPTION, j5_trans('参数错误'), [
                'errors' => $validator->errors()
            ]);
        }

        DB::connection('leguo')->beginTransaction();
        try {

            // 创建商品
            // $goods = Goods::create($request->except('image_ids'));
            $goods = Goods::create(collect($all_data)->except('image_ids')->toArray());

            if ($goods) {

                // 多图片关联
                if ($request->has('image_ids') && is_array($request->image_ids)) {
                    $syncData = [];
                    foreach ($request->image_ids as $k => $imgId) {
                        $syncData[$imgId] = [
                            'type' => 'gallery', // 可自定义类型
                            'sort_order' => $k
                        ];
                    }
                    // $goods->images()：调用商品模型的 多态多对多关联（返回一个 Eloquent 关联对象）
                    // sync($syncData)：批量同步关联关系到中间表（这里是 imageables）
                    $goods->images()->sync($syncData);
                }

                $goods->load('images');

                DB::connection('leguo')->commit();

                return api_res(APICodeEnum::SUCCESS, j5_trans('创建成功'), GoodsResource::make($goods));
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, Goods $good): JsonResponse
    {
        $all_data = $request->all();
        $validator = validator($all_data, [
            // 'name' => 'nullable|string|max:255|unique:leguo.goods,name,' . $good->id,
            'name' => 'nullable|string|max:255',
            'desc' => 'nullable|string|max:500',
            'stock_num' => 'nullable|integer|min:0',
            'price' => 'nullable|numeric|min:0',
            // currency 必须3位
            'currency' => 'nullable|string|size:3',
            'status' => 'nullable|boolean',
            'image_ids' => 'array',
            'image_ids.*' => 'integer|exists:leguo.images,id',
        ]);

        if ($validator->fails()) {
            return api_res(APICodeEnum::EXCEPTION, j5_trans('参数错误'), [
                'errors' => $validator->errors()
            ]);
        }

        DB::connection('leguo')->beginTransaction();
        try {
            // 更新商品
            $good->update(collect($all_data)->except('image_ids')->toArray());

            if ($good) {
                // 如果有图片id则同步图片关联
                if ($request->has('image_ids') && is_array($request->image_ids)) {
                    $syncData = [];
                    foreach ($request->image_ids as $k => $imgId) {
                        $syncData[$imgId] = [
                            'type' => 'gallery', // 你可以自定义类型
                            'sort_order' => $k
                        ];
                    }
                    $good->images()->sync($syncData);
                }

                $good->load('images');

                DB::connection('leguo')->commit();

                return api_res(APICodeEnum::SUCCESS, j5_trans('更新成功'), GoodsResource::make($good));
            } else {
                DB::connection('leguo')->rollBack();

                return api_res(APICodeEnum::SUCCESS, j5_trans('更新失败'));
            }
        } catch (\Exception $e) {
            DB::connection('leguo')->rollBack();

            return api_res(APICodeEnum::EXCEPTION, $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Goods $good): JsonResponse
    {
        // 商品信息不能真的删除，只能假删除
        // 检查商品是否存在
        if ($good->status == DefaultStatusEnum::DELETED) {
            return api_res(APICodeEnum::EXCEPTION, j5_trans('数据不存在'));
        }

        // 假删除商品
        $good->status = DefaultStatusEnum::DELETED;
        $good->save();

        return api_res(APICodeEnum::SUCCESS, j5_trans('操作成功'), GoodsResource::make($good));
    }
}
