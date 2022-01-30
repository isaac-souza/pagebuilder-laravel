<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\HasUUID;
use App\Models\Account;

class LandingPage extends Model
{
    use HasFactory;
    use HasUUID;
    use SoftDeletes;

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'pages',
        'draft',
    ];

    protected $casts = [
        'pages' => 'array',
        'draft' => 'array',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

}
