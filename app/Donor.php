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
      'image_url',
      'organization_id',
      'donor_type_id',
      'location_id',

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
