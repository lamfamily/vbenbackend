<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dept extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'order',
        'parent_id',
        'remark',
    ];


    public function parent(): BelongsTo
    {
        return $this->belongsTo(Dept::class, 'parent_id');
    }


    public function children()
    {
        return $this->hasMany(Dept::class, 'parent_id')
            ->where('status', true)
            ->orderBy('order');
    }


    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }
}
