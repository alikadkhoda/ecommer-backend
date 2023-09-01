<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;
    protected $timestamps=false;
    protected $table='profiles';
    protected $fillable=[
        'image',
        'birthday'
    ];
}