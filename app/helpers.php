<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Image;
use App\Models\Account;

if (! function_exists('user'))
{
    /**
     * Returns the current logged in user
     *  
     * @return \App\Models\User|null
     * */
    function user(): User|null
    {
        /** @var \App\Models\User|null $user */
        $user = auth()->user();

        return $user;
    }
}

if (! function_exists('account'))
{
    /**
     * Returns the current logged in user's account
     *  
     * @return \App\Models\Account|null
     * */
    function account(): Account|null
    {
        /** @var \App\Models\Account|null $account */
        $account = auth()->user()->account;

        return $account;
    }
}

if (! function_exists('uuid'))
{
    /**
     * Generated a new UUID v4
     *  
     * @return string
     * */
    function uuid(): string
    {
        return (string) Str::uuid();
    }
}

if (! function_exists('getImageUrlFromUuid'))
{
    /**
     * Generated a new UUID v4
     *  
     * @return string
     * */
    function getImageUrlFromUuid(string|null $uuid): string
    {
        return Storage::disk('public')->url(Image::find($uuid)?->path);
    }
}
