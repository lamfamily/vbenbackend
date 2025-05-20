<?php

namespace Modules\Leguo\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Leguo\Database\Factories\GoodsCategoryFactory;

class GoodsCategory extends Model
{
    use HasFactory;

    protected $connection = 'leguo';
    protected $table = 'goods_category';

    /**
     * The attributes that are mass assignable.
     */
    // protected $fillable = [];
    protected $guarded = [];

    protected static function newFactory(): GoodsCategoryFactory
    {
        return GoodsCategoryFactory::new();
    }

    public function goods() : BelongsToMany
    {
        return $this->belongsToMany(Goods::class, 'goods_category_goods', 'goods_category_id', 'goods_id');
    }
}
