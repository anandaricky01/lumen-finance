<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

Class Note extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'user_id',
        'description',
        'count',
        'type',
        'price',
        'date'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
