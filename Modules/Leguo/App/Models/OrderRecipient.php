<?php

namespace Modules\Leguo\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Leguo\Database\factories\OrderRecipientFactory;

/**
 * 
 *
 * @property int $id
 * @property string $order_no
 * @property string|null $suborder_no
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $phone
 * @property string|null $line_id
 * @property string|null $email
 * @property string|null $receive_date
 * @property string|null $receive_time_span
 * @property string|null $id_no
 * @property string|null $address
 * @property string|null $remark
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRecipient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRecipient newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRecipient query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRecipient whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRecipient whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRecipient whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRecipient whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRecipient whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRecipient whereIdNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRecipient whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRecipient whereLineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRecipient whereOrderNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRecipient wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRecipient whereReceiveDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRecipient whereReceiveTimeSpan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRecipient whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRecipient whereSuborderNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderRecipient whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrderRecipient extends Model
{
    use HasFactory;

    protected $connection = 'leguo';
    protected $table = 'order_recipient';

    /**
     * The attributes that are mass assignable.
     */
    // protected $fillable = [];
    protected $guarded = [];

}
