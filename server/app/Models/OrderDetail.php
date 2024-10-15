<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'Ord_id',
        'Serial_id',
        'OrdDtl_Price',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'Ord_id');
    }


    public function serials()
    {
        return $this->belongsTo(Serial::class, 'Serial_id');
    }

    
}
