<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuickAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_umi',
        'package_type',
        'purchased_location',
        'cost',
        'weight',
        'shipping_company',
        'tracking_number',
        'company_tracking_number'
    ];


    public function member()
    {
        return $this->belongsTo(Member::class, 'member_umi', 'umi');
    }
}
