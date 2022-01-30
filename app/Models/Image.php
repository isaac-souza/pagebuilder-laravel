<?php

namespace App\Models;

use App\Models\Concerns\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use HasFactory;
    use HasUUID;
    use SoftDeletes;

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

}
