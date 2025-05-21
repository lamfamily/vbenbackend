<?php

namespace Modules\Leguo\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Leguo\Database\factories\ImageFactory;

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
