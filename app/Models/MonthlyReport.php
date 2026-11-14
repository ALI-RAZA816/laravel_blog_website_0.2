<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyReport extends Model
{
     protected $fillable = [
        'user_id',
        'month',
        'year',
        'total_post',
    ];
}
