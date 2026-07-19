<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'last_login_at',
        'last_login_ip',
        'last_download_file_at',
        'last_download_file_id',
        'last_delete_file_at',
        'last_delete_file_id'
    ];
}
