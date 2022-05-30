<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property string $image
 * @property string $description
 * @property string $name
 * @property int $manufacturer_id
 *
 * Class Product
 * @package App\Models
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image'
    ];

    /**
     * @return BelongsToMany
     */
    public function manufacturer(): BelongsToMany
    {
        return $this->belongsToMany(Manufacturer::class,'manufacturer_product','product_id','manufacturer_id');
    }
}
