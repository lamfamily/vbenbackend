<?php

namespace Modules\Leguo\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

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
