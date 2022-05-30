<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property string $title
 * @property string $country
 *
 * Class Manufacturer
 * @package App\Models
 */
class Manufacturer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
      'title',
      'country'
    ];
    protected $table = 'manufacturers';

    /**
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class,'manufacturer_product','manufacturer_id','product_id');
    }
}
