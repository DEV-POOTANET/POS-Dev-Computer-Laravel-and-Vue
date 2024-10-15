<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'Cus_id',
        'Emp_id',
        'Ord_Date',
        'Ord_Time',
        'Ord_TotalAmount',
        'Ord_Payment',
        'Ord_Status',
    ];

    public function users()
    {
        return $this->belongsTo('App\Models\User','Emp_id')->select(['id','fullname','role']);
    }
    public function customers()
    {
        return $this->belongsTo('App\Models\Customer','Cus_id')->select(['id','Cus_fullname']);
    }

    public function order_details()
    {
        return $this->hasMany(OrderDetail::class)->orderBy('id','desc');
    }
}
