<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'city',
        'postal_code',
        'country',
    ];

    /**
     * Scope para filtrar por ciudad
     */
    public function scopeByCity($query, $city)
    {
        return $query->where('city', $city);
    }

    /**
     * Scope para filtrar por país
     */
    public function scopeByCountry($query, $country)
    {
        return $query->where('country', $country);
    }

    /**
     * Obtener dirección completa formateada
     */
    public function getFullAddressAttribute()
    {
        return trim("{$this->address}, {$this->city}, {$this->postal_code}, {$this->country}");
    }

    /**
     * Verificar si tiene dirección completa
     */
    public function hasCompleteAddress()
    {
        return !empty($this->address) &&
               !empty($this->city) &&
               !empty($this->postal_code);
    }
}
