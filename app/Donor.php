<?php

namespace FSR;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Donor extends Authenticatable
{
    use Notifiable;

    protected $type = "donor";

    public function type()
    {
        return 'donor';
    }

    /**
     * Get the location for this donor.
     */
    public function location()
    {
        return $this->belongsTo('FSR\Location');
    }

    /**
     * Get the organization for this donor.
     */
    public function organization()
    {
        return $this->belongsTo('FSR\Organization');
    }

    /**
     * Get the donor_type for this donor.
     */
    public function donor_type()
    {
        return $this->belongsTo('FSR\DonorType');
    }

    /**
     * Get the listings for this donor.
     */
    public function listings()
    {
        return $this->hasMany('FSR\Listing');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'email',
      'password',
      'first_name',
      'last_name',
      'phone',
      'address',
      'profile_image_id',
      'organization_id',
      'donor_type_id',
      'location_id',
      'notifications',
      'approved',

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
 * Get the phone record associated with the user.
 */
    // public function phone()
    // {
    //     return $this->hasMany('App\Phone');
    // }
}
