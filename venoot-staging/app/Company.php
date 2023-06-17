<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'social_reason', 'area', 'rut', 'address', 'city', 'commune_id', 'region_id', 'phone', 'logo', 'payment_type',
    ];

    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function companyRequests()
    {
        return $this->hasMany('App\CompanyRequest');
    }

    public function userRequested()
    {
        return $this->hasManyThrough('App\CompanyRequest', 'App\User');
    }

    public function actors()
    {
        return $this->hasMany('App\Actor');
    }

    public function databases()
    {
        return $this->hasMany('App\Database');
    }

    public function events()
    {
        return $this->hasMany('App\Event');
    }

    public function sponsors()
    {
        return $this->hasMany('App\Sponsor');
    }

    public function polls()
    {
        return $this->hasMany('App\Poll');
    }

    public function orders()
    {
        return $this->hasMany('App\Order');
    }

    public function estimates()
    {
        return $this->hasMany('App\Estimate');
    }

    public function discounts()
    {
        return $this->hasMany('App\Discount');
    }

    public function tickets()
    {
        return $this->hasManyThrough('App\Ticket', 'App\Event');
    }

    public function templates()
    {
        return $this->hasMany('App\Template');
    }
}
