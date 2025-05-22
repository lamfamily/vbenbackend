<?php

namespace Modules\Leguo\App\Services;

use DB;
use App\Enums\OrderStatusEnum;
use App\Enums\DefaultStatusEnum;
use Illuminate\Support\Facades\Log;
use Modules\Leguo\App\Models\Goods;
use Modules\Leguo\App\Models\Order;
use App\Enums\OrderPaymentStatusEnum;
use Modules\Leguo\App\Models\OrderDetail;
use Modules\Leguo\App\Models\OrderRecipient;

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

        echo "<pre>";
        var_dump($total_amount);
        echo "</pre>";
        exit();

        // create order action

        DB::connection('leguo')->beginTransaction();

        $order = new Order();
        $order->order_no = $order_no;
        $order->payment_status = OrderPaymentStatusEnum::UNPAID;
        $order->amount = $total_amount;
        $order->discount = 0;
        $order->status = OrderStatusEnum::ENABLED;
        $order->save();

        $order_recipient = new OrderRecipient();
        $order_recipient->order_no = $order_no;
        $order_recipient->first_name = $recipient['first_name'] ?? '';
        $order_recipient->last_name = $recipient['last_name'] ?? '';
        $order_recipient->phone = $recipient['phone'] ?? '';
        $order_recipient->line_id = $recipient['line_id'] ?? '';
        $order_recipient->email = $recipient['email'] ?? '';
        $order_recipient->id_no = $recipient['id_no'] ?? '';
        $order_recipient->address = $recipient['address'] ?? '';
        $order_recipient->remark = $recipient['remark'] ?? '';
        $order_recipient->save();

        foreach ($goods_list as $key => $value) {
            $goods_id = $value['goods_id'];
            /** @var Goods $goods_data */
            $goods_data = $goods_data_list[$goods_id];
            $tmp_original_stock = $goods_data->stock_num;

            // 减少库存
            Goods::where('id', $goods_id)->where('stock_num', '>=', $value['goods_quantity'])->decrement('stock_num', $value['goods_quantity']);

            $new_goods_data = Goods::find($goods_id);

            $tmp_log_arr = [
                'create order',
                'order_no: ' . $order_no,
                'goods_id: ' . $goods_id,
                'goods_name: ' . $goods_data->name,
                'original_stock: ' . $tmp_original_stock,
                'reduce_stock: ' . $value['goods_quantity'],
                'remaining_stock: ' . $new_goods_data->stock_num,
            ];

            // 记录日志
            Log::channel('leguo_stock')->info(implode("\n", $tmp_log_arr));

            $suborder_no = $order_no . '-' . str_pad($key + 1, 3, '0', STR_PAD_LEFT);

            $order_detail = new OrderDetail();
            $order_detail->order_no = $order_no;
            $order_detail->suborder_no = $suborder_no;
            $order_detail->goods_id = $goods_id;
            $order_detail->goods_name = $goods_data->name;
            $order_detail->goods_price = $goods_data->price;
            $order_detail->goods_quantity = $value['goods_quantity'];
            $order_detail->save();

            $detail_recipient = $value['recipient'] ?? [];
            if ($detail_recipient) {
                $order_detail_recipient = new OrderRecipient();
                $order_detail_recipient->order_no = $order_no;
                $order_detail_recipient->suborder_no = $suborder_no;
                $order_detail_recipient->first_name = $detail_recipient['first_name'] ?? '';
                $order_detail_recipient->last_name = $detail_recipient['last_name'] ?? '';
                $order_detail_recipient->phone = $detail_recipient['phone'] ?? '';
                $order_detail_recipient->line_id = $detail_recipient['line_id'] ?? '';
                $order_detail_recipient->email = $detail_recipient['email'] ?? '';
                $order_detail_recipient->id_no = $detail_recipient['id_no'] ?? '';
                $order_detail_recipient->address = $detail_recipient['address'] ?? '';
                $order_detail_recipient->remark = $detail_recipient['remark'] ?? '';
                $order_detail_recipient->receive_date = $recipient['receive_date'] ?? '';
                $order_detail_recipient->receive_time_span = $recipient['receive_time_span'] ?? '';
                $order_detail_recipient->save();
            }
        }

        DB::connection('leguo')->commit();

        // return $order_no;
        return Order::with(['order_recipient', 'order_detail'])->where('order_no', $order_no)->first();
    }
}
