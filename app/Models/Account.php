<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\LandingPage;
use App\Models\Concerns\HasUUID;

class Account extends Model
{
    use HasFactory;
    use HasUUID;

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    public function landingPages()
    {
        return $this->hasMany(LandingPage::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
