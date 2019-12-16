<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lugar extends Model
{
    //
    use SoftDeletes;

    protected $table = 'lugares';
    protected $guarded = ['id'];
}
