<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function seller()
    {
        return $this->hasOne(User::class, 'userable_id','sellerId')
                   ->where('userable_type', Seller::class);
    }
}
