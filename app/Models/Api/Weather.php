<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Weather extends Model
{
    use HasFactory;

    protected $table = 'weather';

    protected $primaryKey = 'id';

    protected $fillable = [
        'city',
        'temperature',
        'temperature_min',
        'temperature_max',
        'humidity',
    ];

    protected $appends = ['fahrenheit'];

    public function getFahrenheitAttribute()
    {
        $fahrenheit = ($this->temperatura * 9 / 5) + 32;
        return $fahrenheit.' °F';
    }

    protected function city(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => ucfirst($value)
        );
    }

    protected function temperature(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $value.' °C',
        );
    }

    protected function temperatureMin(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $value.' °C',
        );
    }

    protected function temperatureMax(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $value.' °C',
        );
    }

    protected function humidity(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $value.' %',
        );
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
