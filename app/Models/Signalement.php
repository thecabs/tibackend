<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Signalement extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id","signaleur_id","message"
    ] ;

    public function signalee(): BelongsTo{
        return $this->belongsTo(User::class,"user_id","id");
    }
    public function signaleur(): BelongsTo{
        return $this->belongsTo(User::class,"signaleur_id","id");
    }

}
