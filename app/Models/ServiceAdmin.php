<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceAdmin extends Model {

    protected $table = 'service_admin';
    protected $fillable = ['service_id', 'admin_chat_id'];

}