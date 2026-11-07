<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Property extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'property_type_id',
        'user_id',
        'title',
        'description',
        'address',
        'city',
        'neighborhood',
        'price',
        'area',
        'bedrooms',
        'bathrooms',
        'operation_type',
        'status',
        'main_image',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'area' => 'decimal:2',
            'bedrooms' => 'integer',
            'bathrooms' => 'integer',
            'operation_type' => 'string',
            'status' => 'string',
            'user_id' => 'integer',
            'property_type_id' => 'integer',
        ];
    }

    public function propertyType(): BelongsTo
    {
        return $this->belongsTo(PropertyType::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** An interest does not reserve or sell the property. */
    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(Client::class, 'client_property')
            ->withTimestamps();
    }

    public function visits(): HasMany
    {
        return $this->hasMany(PropertyVisit::class);
    }

    public function galleryImages(): HasMany
    {
        return $this->hasMany(PropertyImage::class)->oldest('id');
    }

    /** @return array<int, string> */
    public function getImagesAttribute(): array
    {
        return array_values(array_filter([
            $this->main_image,
            ...$this->galleryImages->pluck('path')->all(),
        ]));
    }
}
