<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\HasUUID;
use App\Models\Account;

class LandingPage extends Model
{
    use HasFactory;
    use HasUUID;

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'pages',
        'drafts',
    ];

    protected $casts = [
        'pages' => 'array',
        'drafts' => 'array',
    ];

    protected $attributes = [
        'unpublished_changes' => false,
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

}
