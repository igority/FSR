<?php

namespace FSR;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    public $timestamps = false;
  
    protected $fillable = [
      'name', 'description', 'type'
  ];
}
