<?php

namespace App\System\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $table = 'submission';

    protected $fillable = ['user_id', 'address', 'subt_date', 'reason', 'approved', 'appr_user', 'appr_date'];

}
