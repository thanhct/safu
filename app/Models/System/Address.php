<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'address';

    protected $fillable = ['hash_address', 'score'];

}
