<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entrada extends Model
{
    /** @use HasFactory<\Database\Factories\InstitutionTypeFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'conta_id',
        'title',
        'category',
        'payment_method',
        'amount',
    ];

    protected $casts = [
        'amount'=>'decimal:2'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function conta(){
        return $this->belongsTo(Conta::class);
    }
}
