<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeoIp extends Model
{
    use HasFactory;

    protected $table = 'geo_ip';

    protected $guarded = [];
}
