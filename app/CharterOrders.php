<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CharterOrders extends Model
{
    /**
     * @var string
     */
    protected $table = 'charter_orders';

    /**
     * @var array
     */
	public $timestamps = true;
	protected $fillable = array('user_id', 'charter_id', 'price', 'pnr',
        'status', 'delivered_by', 'phone', 'flight_class', 'note', 'commission',
        'email');

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function charter()
    {
        return $this->belongsTo(Charter::class, 'charter_id');
    }

    public function flights()
    {
        return $this->hasMany(CharterOrderFlights::class, 'order_id');
    }

    public function history()
    {
        return $this->hasMany(CharterHistory::class, 'order_id')->orderBy('id', 'desc');
    }

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function passengers()
	{
		return $this->hasMany(CharterPassengers::class, 'order_id');
	}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function deliveredBy()
    {
        return $this->belongsTo(User::class, 'delivered_by');
    }
}