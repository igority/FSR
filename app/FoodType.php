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
     * Get the products for this food_type.
     */
    public function products()
    {
        return $this->hasMany('FSR\Product');
    }
}
