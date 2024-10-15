<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'Cus_fullname',
        'Cus_tel',
        'Cus_email',
        'Cus_Status',
    ];
}
