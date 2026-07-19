<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailDepartement extends Model
{
    use HasFactory;
    protected $table = 'detail_departement';

    protected $fillable = [
        'id',
        'code',
        'name',
        'departemen_id',
        'email_director',
        'email_depthead',
        'email_spv',
        'email_members',
    ];

}
