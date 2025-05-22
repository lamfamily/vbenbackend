<?php

namespace Modules\Leguo\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Leguo\Database\factories\WebsiteFactory;

/**
 * 
 *
 * @property int $id
 * @property string|null $icon
 * @property string|null $banner
 * @property string|null $banner2
 * @property string|null $line_url
 * @property string|null $desc
 * @property string $meta_keywords
 * @property string $title
 * @property string $phone
 * @property string $address
 * @property string|null $address_map_url
 * @property string|null $company_desc
 * @property string|null $checkout_desc
 * @property string|null $terms
 * @property string|null $privacy
 * @property string|null $know_le_guo
 * @property string $ecpay_merchant_id
 * @property string $ecpay_hash_key
 * @property string $ecpay_hash_iv
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Website newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Website newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Website query()
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereAddressMapUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereBanner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereBanner2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereCheckoutDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereCompanyDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereEcpayHashIv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereEcpayHashKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereEcpayMerchantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereKnowLeGuo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereLineUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereMetaKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website wherePrivacy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereTerms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Website whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Website extends Model
{
    use HasFactory;

    protected $connection = 'leguo';
    protected $table = 'website';

    /**
     * The attributes that are mass assignable.
     */
    // protected $fillable = [];
    protected $guarded = [];

}
