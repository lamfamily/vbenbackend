<?php

namespace Modules\Leguo\App\Http\Controllers;

use App\Enums\APICodeEnum;
use App\Enums\DefaultStatusEnum;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Modules\Leguo\App\Models\Order;
use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Modules\Leguo\App\resources\OrderResource;

class OrderController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('permission:Leguo:Order:List');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $p = $request->input('p', 1);
        $ps = $request->input('ps', 10);

        $list = Order::orderBy('id', 'desc')->paginate($ps, ['*'], 'page', $p);

        return api_res(APICodeEnum::SUCCESS, j5_trans('获取列表成功'), [
            'items' => OrderResource::collection($list),
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

        $rules = [
            // recipient check
            'recipient' => 'required|array',
            'recipient.first_name' => 'required|string|max:255',
            'recipient.last_name' => 'required|string|max:255',
            'recipient.phone' => [
                'required',
                'string',
                'regex:/^09\d{8}$/',
                'max:10',
            ],
            'recipient.email' => 'required|email|max:255',
            'recipient.address' => 'required|string|max:500',
            'recipient.id_no' => 'string|max:255',
            'recipient.remark' => 'string|max:500',

            // goods_list check
            'goods_list' => 'required|array|min:1',
            // 'goods_list.*.goods_id' => 'required|integer|exists:leguo.goods,id',
            'goods_list.*.goods_id' => [
                'required',
                'integer',
                'exists:leguo.goods,id,status,' . DefaultStatusEnum::ENABLED
            ],
            'goods_list.*.goods_quantity' => 'required|integer|min:1',

            // goods_list.*.recipient（可选，部分商品有）
            'goods_list.*.recipient' => 'nullable|array',
            'goods_list.*.recipient.first_name' => 'required_with:goods_list.*.recipient|string|max:255',
            // 'goods_list.*.recipient.last_name' => 'required_with:goods_list.*.recipient|string|max:255',
            'goods_list.*.recipient.phone' => [
                'required_with:goods_list.*.recipient',
                'string',
                'regex:/^09\d{8}$/',
                'max:10',
            ],
            'goods_list.*.recipient.address' => 'required_with:goods_list.*.recipient|string|max:500',
            'goods_list.*.recipient.id_no' => 'string|max:255',
            'goods_list.*.recipient.remark' => 'string|max:500',
        ];

        $validator = validator($all_data, $rules);

        if ($validator->fails()) {
            return api_res(APICodeEnum::EXCEPTION, j5_trans('参数错误'), [
                'errors' => $validator->errors()
            ]);
        }

        try {
            $order = (new OrderService)->createOrder($all_data);

            return api_res(APICodeEnum::SUCCESS, j5_trans('创建成功'), OrderResource::make($order));
        } catch (\Exception $e) {
            return api_res(APICodeEnum::EXCEPTION, j5_trans('创建失败'), [
                'errors' => $e->getMessage()
            ]);
        }
    }

    /**
     * Show the specified resource.
     */
    public function show(Request $request, Order $order)
    {
        return api_res(APICodeEnum::SUCCESS, j5_trans('获取成功'), OrderResource::make($order));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order): JsonResponse
    {
        echo "<pre>";
        var_dump('update order');
        echo "</pre>";
        exit();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
