<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountKey extends Model
{
    use HasFactory;
    protected $fillable = [
        'merchant_key',
        'name'
    ];
    public function __toString()
    {
        return $this->name;
    }
    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
