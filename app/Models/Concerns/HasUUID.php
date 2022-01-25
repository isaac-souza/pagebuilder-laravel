<?php

declare (strict_types = 1);

namespace App\Models\Concerns;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

trait HasUUID
{  
    public static function bootHasUUID() {
        static::creating(function(Model $model) {
            $model->uuid = Str::uuid();
        });
    }
}
