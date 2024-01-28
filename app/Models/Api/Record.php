<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Record extends Model
{
    use HasFactory;

    protected $table = 'records';

    protected $primaryKey = 'id';

    protected $fillable = [
        'type',
        'description',
    ];

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    protected function type(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => ucfirst($value)
        );
    }

    protected function description(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => ucfirst($value)
        );
    }
}
