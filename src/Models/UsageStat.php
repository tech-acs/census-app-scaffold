<?php

namespace Uneca\Scaffold\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsageStat extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
