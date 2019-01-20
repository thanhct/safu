<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use App\Models\Auth\User;

class UserRep extends Model
{
	public $timestamps = false;

    protected $table = 'user_rep';

    protected $fillable = ['user_id', 'reps', 'balance'];

    public function user()
    {
        return $this->hasOne(User::class, 'user_id');
    }

}
