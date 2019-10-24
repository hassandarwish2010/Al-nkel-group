<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CharterOrderFlights extends Model
{

    protected $table = 'charter_order_flights';
    public $timestamps = false;
    protected $fillable = array('order_id', 'charter_id', 'price', 'price', 'commission', 'flight_class');

	public function charter()
	{
		return $this->belongsTo(Charter::class, 'charter_id');
	}

}