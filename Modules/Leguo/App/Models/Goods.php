<?php

namespace Modules\Leguo\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Leguo\Database\factories\GoodsFactory;

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

    public function GoodsCategories() : BelongsToMany
    {
        return $this->belongsToMany(GoodsCategory::class, 'goods_category_goods', 'goods_id', 'goods_category_id');
    }
}
