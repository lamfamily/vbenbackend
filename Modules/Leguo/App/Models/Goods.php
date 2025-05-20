<?php

namespace Modules\Leguo\App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Leguo\Database\Factories\GoodsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
}
