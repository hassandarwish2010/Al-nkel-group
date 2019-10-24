<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VisaOrders extends Model
{
    /**
     * @var string
     */
    protected $table = 'visa_orders';

    /**
     * @var array
     */
    protected $fillable = ['visa_id', 'user_id', 'first_name', 'last_name', 'birth_place', 'birth_date', 'nationality',
        'passport_number', 'passport_issuance_date', 'passport_expire_date', 'father_name',
        'mother_name', 'price', 'passport_image', 'personal_image', 'status', 'delivered_by', 'visa_pdf'];

    /**
     * @var array
     */
    protected $casts = [
        'birth_date' => 'date',
        'passport_issuance_date' => 'date',
        'passport_expire_date' => 'date',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function visa()
    {
        return $this->belongsTo(Visa::class, 'visa_id');
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
