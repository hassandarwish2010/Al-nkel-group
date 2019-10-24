<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Locked extends Model
{
    /**
     * @var string
     */
    protected $table = 'charter_locked';

    /**
     * @var array
     */
    protected $fillable = ['charter_id', 'user_id', 'day', 'seats'];
}
