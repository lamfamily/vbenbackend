<?php

namespace Modules\Leguo\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Leguo\Database\Factories\GoodsCategoryFactory;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $desc
 * @property int $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Leguo\App\Models\Goods> $goods
 * @property-read int|null $goods_count
 * @method static \Modules\Leguo\Database\Factories\GoodsCategoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsCategory whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsCategory whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GoodsCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
