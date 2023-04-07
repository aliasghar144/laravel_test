<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class accepted_requests extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id'
    ];
}
