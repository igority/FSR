<?php

namespace FSR;

use Illuminate\Database\Eloquent\Model;

class FoodType extends Model
{
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'name', 'comment', 'default_image'
  ];

    /**
     * Get the listings for this food_type.
     */
    public function listings()
    {
        return $this->hasMany('FSR\Listing');
    }
}
