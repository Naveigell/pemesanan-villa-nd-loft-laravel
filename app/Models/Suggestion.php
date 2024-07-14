<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{
    use HasFactory;

    protected $fillable = ['user_id'];

    /**
     * Retrieve the details associated with the suggestion.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function details()
    {
        return $this->hasMany(SuggestionDetail::class, 'suggestion_id');
    }

    /**
     * Retrieve the latest details associated with the suggestion.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function latestDetails()
    {
        return $this->hasMany(SuggestionDetail::class, 'suggestion_id')->latest('id');
    }

    /**
     * Retrieve the latest detail associated with the suggestion.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function latestDetail()
    {
        return $this->hasOne(SuggestionDetail::class, 'suggestion_id')->latestOfMany();
    }

    /**
     * Retrieve the user associated with the suggestion.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
