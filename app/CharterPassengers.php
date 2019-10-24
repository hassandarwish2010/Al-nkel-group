<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CharterPassengers extends Model 
{

    protected $table = 'charter_passengers';
    public $timestamps = true;
    protected $fillable = array('order_id', 'title', 'first_name', 'last_name',
        'birth_date', 'nationality', 'passport_number', 'passport_expire_date',
        'price', 'age');

    protected $appends = ['ticket_number', 'name'];

	public function related()
	{
		return $this->hasMany(CharterPassengersRelated::class, 'passenger_id');
	}

    public function getTicketNumberAttribute() {
		return $this->related()->pluck('ticket_number');
    }

    public function getNameAttribute() {
		return $this->title . ' ' . $this->first_name . ' ' . $this->last_name;
    }

    public function getPassengerNationalityAttribute() {
		return \App\Nationality::find( $this->nationality )->name['en'];
    }

}