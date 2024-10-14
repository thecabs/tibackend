<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'annonce_id',
        'places',
        'note',
        'etat'
    ];

    public function annonce():BelongsTo{
        return $this->belongsTo(Annonce::class);
    }
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
