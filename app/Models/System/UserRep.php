<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class UserRep extends Model
{
	public $timestamps = false;

    protected $table = 'user_rep';

    protected $fillable = ['user_id', 'reps', 'balance'];
}
