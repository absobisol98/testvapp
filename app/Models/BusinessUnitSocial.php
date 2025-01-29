<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessUnitSocial extends Model
{


    protected $table = 'business_unit_socials';

    protected $fillable = [
        'social',
        'link',
        'business_unit_id'
    ];

    /**
     * @var array<string, string>
     */
    // protected $casts = [
    //     'is_active' => 'boolean',
    // ];

    public function businessUnit()
    {
        return $this->belongsTo(BusinessUnit::class, 'business_unit_id');
    }



    //
}
