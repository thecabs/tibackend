<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ["user_id","admin_id","etat","message"] ;

    public function notifie(): BelongsTo{
        return $this->belongsTo(User::class,"user_id");
    }

    public function notifieur(): BelongsTo{
        return $this->belongsTo(User::class,"admin_id");
    }


}
