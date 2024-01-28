<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';

    protected $primaryKey = 'id';

    protected $fillable = [
        'comment',
    ];

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    protected function comment(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => ucfirst($value)
        );
    }
}
