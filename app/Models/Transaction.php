<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'payee_id',
        'payer_id',
        'value'
    ];

    public function payee(){
        return $this->belongsTo(Account::class,'payee_id');
    }

    public function payer(){
        return $this->belongsTo(Account::class,'payer_id');
    }
}
