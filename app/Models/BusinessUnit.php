<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\Conversions\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessUnit extends Model implements HasMedia
{

    use InteractsWithMedia;

    protected $table = 'business_units';

    protected $fillable = [
        'name',
        'nickname',
        'slug',
        'about',
        'header_tagline',
        'header_description',
        'created_by',
        'cluster_id',
        'company_id',
    ];

    /**
     * @var array<string, string>
     */


    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('preview')
            // ->crop(Manipulations, 400, 400)
            ->performOnCollections('bu_logo')
            ->format('webp')
            ->sharpen(10);


        $this->addMediaConversion('gallery')
            // ->fit(Manipulations::FIT_CROP, 400, 350)
            ->performOnCollections('bu_galleries')
            ->format('webp')
            ->sharpen(10);

        $this->addMediaConversion('cover')
            ->performOnCollections('bu_eventcover')
            // ->fit(Manipulations::FIT_CONTAIN, 1500, 2000)
            ->format('webp')
            ->sharpen(10);

        $this->addMediaConversion('gallery')
            // ->fit(Manipulations::FIT_CROP, 400, 350)
            ->performOnCollections('bu_galleries_head')
            ->format('webp')
            ->sharpen(10);
    }



    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }


    public function socials()
    {
        return $this->hasMany(BusinessUnitSocial::class, 'business_unit_id');
    }

    public function admins()
    {
        return $this->belongsToMany(User::class, 'business_unit_has_external_admin','business_unit_id', 'user_id');
    }

    public function cluster(): BelongsTo
    {
        return $this->belongsTo(Cluster::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
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
