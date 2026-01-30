<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsabilityFeedback extends Model
{
    use HasFactory;

    // "feedback" is uncountable, so override the default table name.
    protected $table = 'usability_feedbacks';

    protected $fillable = [
        'user_id',
        'rating',
        'comment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
