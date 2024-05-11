<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use App\Models\Scopes\ArchivedScope;

#[ScopedBy([ArchivedScope::class])]
class City extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'latitude', 'longitude', 'postal_code', 'country_id', 'archived'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
