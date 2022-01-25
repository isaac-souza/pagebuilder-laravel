<?php

namespace App\Http\Controllers;

use App\Http\Resources\AccountResource;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function check()
    {
        if(is_null(user()))
        {
            return response()->json(['authenticated' => false], Response::HTTP_OK);
        }
        
        return response()->json(['authenticated' => true], Response::HTTP_OK);
    }

    public function account()
    {
        return new AccountResource(account());
    }
}
