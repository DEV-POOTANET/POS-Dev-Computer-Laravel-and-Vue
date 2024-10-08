<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cat extends Model
{
    use HasFactory;

    protected $fillable =[
        'Cat_Name',
        'Cat_Status',
    ];

    public function products()
    {
        return $this->hasMany(Product::class)->orderBy('id','desc');
    }
    public function activeProducts()
    {
        return $this->hasMany(Product::class)->where('Prd_Status', 1); // Example for active products
    }
}
