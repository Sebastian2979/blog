<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\Failed\FileFailedJobProvider;

class Follower extends Model
{
    public const UPDATED_AT = null;
    protected $fillable = ['user_id', 'follower_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function follower()
    {
        return $this->belongsTo(User::class);
    }
}
