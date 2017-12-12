<?php

namespace FSR;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Cso extends Authenticatable
{
    use Notifiable;

    protected $type = "cso";

    public function type()
    {
        return 'cso';
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
}
