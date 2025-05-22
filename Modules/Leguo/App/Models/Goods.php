<?php

namespace Modules\Leguo\App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Leguo\Database\Factories\GoodsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $desc
 * @property int $stock_num
 * @property string $price
 * @property string $currency
 * @property int $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Leguo\App\Models\GoodsCategory> $goodsCategories
 * @property-read int|null $goods_categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Leguo\App\Models\Image> $images
 * @property-read int|null $images_count
 * @method static \Modules\Leguo\Database\Factories\GoodsFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Goods newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Goods newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Goods query()
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereStockNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Goods whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Goods extends Model
{
    use HasFactory;

    protected $connection = 'leguo';
    protected $table = 'goods';

    /**
     * The attributes that are mass assignable.
     */
    // protected $fillable = [];
    protected $guarded = [];

    protected static function newFactory(): GoodsFactory
    {
        return GoodsFactory::new();
    }

    /*
     * model 的所有查询，自动过滤掉 status = -1 的数据，视为找不到
     * 隐式模型绑定,也会找不到
     * 如果要查询到 status = -1 的数据，需要使用 Goods::withoutGlobalScope('active')->where('status', -1)->get();
     * @author: Justin Lin
     * @date : 2025-05-20 17:49:44
     */
    protected static function booted()
    {
        static::addGlobalScope('active', function ($query) {
            $query->where('status', '!=', -1);
        });
    }

    public function goodsCategories(): BelongsToMany
    {
        return $this->belongsToMany(GoodsCategory::class, 'goods_category_goods', 'goods_id', 'goods_category_id');
    }


    public function images() : MorphToMany
    {
        return $this->morphToMany(
            Image::class,
            'imageable' // 多态字段的名字，对应 imageables 这张表
        )->withPivot(['type', 'sort_order'])->withTimestamps();
    }
}
