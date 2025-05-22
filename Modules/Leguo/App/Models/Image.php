<?php

namespace Modules\Leguo\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Leguo\Database\factories\ImageFactory;

/**
 * 
 *
 * @property int $id
 * @property string $hash
 * @property string $path 存储路径
 * @property string $url 公开访问地址
 * @property int $size 文件大小
 * @property string $mime MIME 类型
 * @property int|null $width 宽度
 * @property int|null $height 高度
 * @property string|null $ext 扩展名
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Leguo\App\Models\Goods> $goods
 * @property-read int|null $goods_count
 * @property-read Model|\Eloquent $imageables
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Leguo\App\Models\Partner> $partner
 * @property-read int|null $partner_count
 * @method static \Illuminate\Database\Eloquent\Builder|Image newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Image newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Image query()
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereExt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereMime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereWidth($value)
 * @mixin \Eloquent
 */
class Image extends Model
{
    use HasFactory;

    protected $connection = 'leguo';
    protected $table = 'images';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'hash',
        'path',
        'url',
        'size',
        'mime',
        'width',
        'height',
        'ext'
    ];

    public function imageables()
    {
        return $this->morphTo();
    }

    public function goods()
    {
        return $this->morphedByMany(Goods::class, 'imageable');
    }

    public function partner()
    {
        return $this->morphToMany(Partner::class, 'imageable');
    }
}
