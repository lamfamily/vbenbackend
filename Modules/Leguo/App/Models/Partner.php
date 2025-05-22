<?php

namespace Modules\Leguo\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $url
 * @property string|null $desc
 * @property int $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Leguo\App\Models\Image> $logo
 * @property-read int|null $logo_count
 * @method static \Illuminate\Database\Eloquent\Builder|Partner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Partner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Partner query()
 * @method static \Illuminate\Database\Eloquent\Builder|Partner whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Partner whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Partner whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Partner whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Partner whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Partner whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Partner whereUrl($value)
 * @mixin \Eloquent
 */
class Partner extends Model
{
    use HasFactory;

    protected $connection = 'leguo';
    protected $table = 'partner';

    /**
     * The attributes that are mass assignable.
     */
    // protected $fillable = [];
    protected $guarded = [];

    /*
     * 定义不了 MorphOne 关系,因为 imageables 结构本身是多对多，不适合用 morphOne。
     * @author: Justin Lin
     * @date : 2025-05-21 14:52:32
     */
    public function logo(): MorphToMany
    {
        return $this->morphToMany(Image::class, 'imageable')
            ->withPivot(['type', 'sort_order'])
            ->wherePivot('type', 'logo')
            ->withTimestamps();
    }
}
