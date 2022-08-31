<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    const TYPE_COMMON = 0;
    const TYPE_STOREKEEPER = 1;

    public function transactionsAsPayer(){
        return $this->hasMany(Transaction::class,'payer_id');
    }

    public function transactionsAsPayee(){
        return $this->hasMany(Transaction::class,'payee_id');
    }
}
