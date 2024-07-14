<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuggestionDetail extends Model
{
    use HasFactory;

    protected $fillable = ['suggestion_id', 'user_id', 'message'];

    /**
     * Determine if the current user is the author of the suggestion detail.
     *
     * @return bool
     */
    public function isFromItSelf()
    {
        return auth()->user()->is($this->user);
    }

    /**
     * Get the user that owns the suggestion detail.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the suggestion that owns the suggestion detail.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function suggestion()
    {
        return $this->belongsTo(Suggestion::class);
    }
}
