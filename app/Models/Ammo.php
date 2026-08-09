<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ammo extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'name',
        'ammo_type',
        'damage',
        'armor_penetration',
    ];

     /**
     * The attributes that should be cast to native types.
     * 
     * @var array
     */
    protected $casts = [
        'damage' => 'integer',
        'armor_penetration' => 'integer',
    ];
}
