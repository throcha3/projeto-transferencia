<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class TransferOutOfRulesException extends Exception
{
    public function render()
    {
        return response()->json([
            'message' => 'Your transfer is not allowed due to internal rules.'
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
