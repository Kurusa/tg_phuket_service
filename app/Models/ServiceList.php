<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceList extends Model {

    protected $table = 'service_list';
    protected $fillable = ['title', 'alias'];

}