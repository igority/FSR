<?php

namespace FSR;

use Illuminate\Database\Eloquent\Model;

class ListingOffer extends Model
{
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
        return $this->belongsTo('FSR\Lsting');
    }
}
