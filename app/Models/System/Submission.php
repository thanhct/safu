<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
	public $timestamps = false;

    protected $table = 'submission';

    protected $fillable = ['id', 'user_id', 'address', 'subt_date', 'reason', 'approved', 'appr_user', 'appr_date'];

}
