<?php

namespace Modules\Leguo\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Leguo\Database\factories\OrderDetailFactory;

/**
 * 
 *
 * @property int $id
 * @property string|null $order_no
 * @property string|null $suborder_no
 * @property int|null $goods_id
 * @property string|null $goods_name
 * @property string|null $goods_price
 * @property string|null $goods_currency
 * @property int|null $goods_quantity
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereGoodsCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereGoodsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereGoodsName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereGoodsPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereGoodsQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereOrderNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereSuborderNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrderDetail extends Model
{
    use HasFactory;

    protected $connection = 'leguo';
    protected $table = 'order_detail';

    /**
     * The attributes that are mass assignable.
     */
    // protected $fillable = [];
    protected $guarded = [];
}
