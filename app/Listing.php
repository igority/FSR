<?php

namespace FSR;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
          'donor_id',
          'title',
          'description',
          'food_type_id',
          'quantity',
          'quantity_type_id',
          'date_listed',
          'date_expires',
          'pickup_time_from',
          'pickup_time_to',
          'listing_status',
          'image_id',
      ];

    /**
     * Get the listing_offers for this listing.
     */
    public function listing_offers()
    {
        return $this->hasMany('FSR\ListingOffer');
    }

    /**
     * Get the donor for this listing.
     */
    public function donor()
    {
        return $this->belongsTo('FSR\Donor');
    }

    /**
     * Get the quantity_type for this listing.
     */
    public function quantity_type()
    {
        return $this->belongsTo('FSR\QuantityType');
    }

    /**
     * Get the food_type for this listing.
     */
    public function food_type()
    {
        return $this->belongsTo('FSR\FoodType');
    }
}
