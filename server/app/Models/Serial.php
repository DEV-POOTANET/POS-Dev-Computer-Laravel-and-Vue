<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Serial extends Model
{
    use HasFactory;
    protected $fillable =[
        'Prd_id',
        'Serial_SerialNumber',
        'Serial_Status',
    ];
    public function products()
    {
        return $this->belongsTo('App\Models\Product','Prd_id')->select(['id','Prd_Name','Prd_Status']);
    }
}
