<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessUnit extends Model
{

    protected $table = 'business_units';

    protected $fillable = [
        'name',
        'nickname',
        'address',
        'slug',
        'about',
        'header_tagline',
        'header_description',
        'event_heading',
        'event_description',
        'created_by',
    ];

    /**
     * @var array<string, string>
     */

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }


    public function socials()
    {
        return $this->hasMany(BusinessUnitSocial::class, 'business_unit_id');
    }

    // public function children()
    // {
    //     return $this->hasMany(BannerCategory::class, 'parent_id');
    // }

    // public function banners()
    // {
    //     return $this->hasMany(Banner::class, 'banner_category_id');
    // }

}
