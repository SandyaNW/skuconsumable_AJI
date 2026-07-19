<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'parent_id',
        'created_by'
    ];

    protected $guarded = [];

    public function subcategory(){

        // return $this->hasMany('App\Models\Category', 'parent_id');
        return $this->hasMany('App\Models\Category', 'parent_id');

    }
}
