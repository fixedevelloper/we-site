<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    public function zone() {
        return $this->belongsTo(Zone::class, 'zone_id', 'id');
    }
}
