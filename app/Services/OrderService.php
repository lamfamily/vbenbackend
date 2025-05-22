<?php

namespace App\Services;

use App\Enums\DefaultStatusEnum;
use DB;
use Modules\Leguo\App\Models\Goods;
use Modules\Leguo\App\Models\Order;

class OrderService
{
    public function createOrder($data)
    {
        $order_no = gen_leguo_order_no();

        $goods_list = $data['goods_list'] ?? [];
        $recipient = $data['recipient'] ?? [];

        $goods_id_arr = array_column($goods_list, 'goods_id');
        $goods_data_list = Goods::whereIn('id', $goods_id_arr)->where('status', DefaultStatusEnum::ENABLED)->get()->keyBy('id');

        if (count($goods_id_arr) != count($goods_data_list)) {
            throw new \Exception(j5_trans('商品不存在'));
        }

        $total_amount = 0;
        foreach ($goods_list as $value) {
            $goods_id = $value['goods_id'];
            $goods_data = $goods_data_list[$goods_id];

            if ($goods_data->stock_num < $value['goods_quantity']) {
                throw new \Exception(j5_trans('商品库存不足'));
            }

            // $total_amount += $goods_data->price * $value['goods_quantity'];
            $amount = bcmul($goods_data->price, $value['goods_quantity'], 2);
            $total_amount = bcadd($total_amount, $amount, 2);
        }

        // create order action

        DB::connection('leguo')->beginTransaction();

        $order = new Order();
        $order->order_no = $order_no;

        DB::commit();

        return $order_no;
    }
}
