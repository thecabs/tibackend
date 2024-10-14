<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Annonce extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'depart',
        'arrivee',
        'date',
        'places',
        'reservation',
        'commentaire',
        'detailsVoiture',
        'etat',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
    public function reservations():HasMany{
        return $this->hasMany(Reservation::class);
    }
}
