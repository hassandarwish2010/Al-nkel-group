<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class Charter extends Model
{

	protected $table = 'charter';
	public $timestamps = true;

	use SoftDeletes;

	protected $dates = ['deleted_at'];
	protected $fillable = array('name', 'from_where', 'to_where', 'flight_type', 'flight_number', 'aircraft_id',
        'flight_date', 'departure_time', 'arrival_time', 'economy_seats', 'business_seats', 'can_cancel',
        'show_in_home', 'business_adult', 'business_child', 'business_baby', 'price_adult', 'price_child',
        'price_baby', 'price_business_2way', 'price_adult_2way', 'price_child_2way', 'price_baby_2way',
        'commission', 'is_percent', 'instructions', 'pay_later_max', 'cancel_fees', 'void_max', 'can_change',
        'change_fees');

	protected $appends = ['flight_day'];

	public function orders()
	{
		return $this->hasMany(CharterOrders::class, 'charter_id');
	}

	public function getFlightDayAttribute()
	{

		return __( 'days.' . date( 'l', strtotime($this->flight_date) ) );
	}

	public function aircraft()
	{
		return $this->belongsTo(Aircraft::class, 'aircraft_id');
	}

	public function from()
	{
		return $this->belongsTo(Country::class, 'from_where');
	}

	public function to()
	{
		return $this->belongsTo(Country::class, 'to_where');
	}

	public function roundtrip()
	{
		return $this->hasOne(Charter2Way::class, 'charter_id');
	}
 
}