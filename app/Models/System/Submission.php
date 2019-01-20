<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use App\Models\Auth\User;

class Submission extends Model
{
	public $timestamps = false;

    protected $table = 'submission';

    protected $fillable = ['id', 'user_id', 'address', 'subt_date', 'reason', 'approved', 'appr_user', 'appr_date'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
