<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Record extends Model {

    protected $table = 'record';
    protected $fillable = ['user_id', 'category_id', 'media', 'title', 'about', 'price', 'button_text', 'status', 'is_video'];
    public $with = ['category'];

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
}