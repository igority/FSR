<?php

namespace FSR;

use Illuminate\Database\Eloquent\Model;

class ListingOffer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    'cso_id',
    'listing_id',
    'offer_status',
    'quantity',
    'beneficiaries_no',
    'volunteer_pickup_name',
    'volunteer_pickup_phone',

  ];
    /**
     * Get the listing_msgs for this listing_offer.
     */
    public function listing_msgs()
    {
        return $this->hasMany('FSR\ListingMsg');
    }

    /**
     * Get the cso for this listing_offer.
     */
    public function cso()
    {
        return $this->belongsTo('FSR\Cso');
    }

    /**
     * Get the listing for this listing_offer.
     */
    public function listing()
    {
        return $this->belongsTo('FSR\Listing');
    }
}
