<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceOrder extends Model {

    protected $table = 'service_order';
    protected $fillable = ['user_id', 'service_id', 'subcategory', 'class', 'rent_period', 'rent_start', 'status', 'room_count'];

}