<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable =[
        'Prd_Name',
        'Prd_details',
        'Cat_id',
        'Prd_Price',
        'Prd_CostPrice',
        'Prd_Img',
        'Prd_Status',
    ];
    public function cats()
    {
        return $this->belongsTo('App\Models\Cat','Cat_id')->select(['id','Cat_Name','Cat_Status']);
    }
}
