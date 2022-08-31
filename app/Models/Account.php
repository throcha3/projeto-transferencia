<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    const TYPE_COMMON = 0;
    const TYPE_STOREKEEPER = 1;

    const URL_EXTERNAL_AUTHORIZER = 'https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6';
    const URL_NOTIFICATION = 'http://o4d9z.mocklab.io/notify';

    const TYPES_ALLOWED_TO_BE_PAYER = [
        self::TYPE_COMMON
    ];

    public function transactionsAsPayer()
    {
        return $this->hasMany(Transaction::class,'payer_id');
    }

    public function transactionsAsPayee()
    {
        return $this->hasMany(Transaction::class,'payee_id');
    }

    public function isAllowedToBePayer(string $type)
    {
        return in_array($type, self::TYPES_ALLOWED_TO_BE_PAYER);
    }
}
