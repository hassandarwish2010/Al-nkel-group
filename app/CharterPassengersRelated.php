<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CharterPassengersRelated extends Model 
{

    protected $table = 'charter_passengers_related';
    public $timestamps = false;
    protected $fillable = array('passenger_id', 'flight_id', 'ticket_number', 'price', 'order_id');

}