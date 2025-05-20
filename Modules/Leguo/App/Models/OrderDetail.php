<?php

namespace Modules\Leguo\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Leguo\Database\factories\OrderDetailFactory;

class OrderDetail extends Model
{
    use HasFactory;

    protected $connection = 'leguo';
    protected $table = 'order_detail';

    /**
     * The attributes that are mass assignable.
     */
    // protected $fillable = [];
    protected $guarded = [];
}
