<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use App\Models\Scopes\ArchivedScope;

#[ScopedBy([ArchivedScope::class])]
class Country extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'archived'];

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }
}
