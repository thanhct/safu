<?php

namespace App\System\Models;

use Illuminate\Database\Eloquent\Model;

class UserRep extends Model
{
    protected $table = 'user_rep';

    protected $fillable = ['user_id', 'reps', 'balance'];

}
