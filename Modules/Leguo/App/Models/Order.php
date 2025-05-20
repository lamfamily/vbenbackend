<?php

namespace Modules\Leguo\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Leguo\Database\factories\OrderFactory;

class Order extends Model
{
    use HasFactory;

    protected $connection = 'leguo';
    protected $table = 'order';

    /**
     * The attributes that are mass assignable.
     */
    // protected $fillable = [];
    protected $guarded = [];

}
