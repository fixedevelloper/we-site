<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfert extends Model
{
    use HasFactory;
    protected $fillable = [
        'sender_id',
        'phone',
        'account_key_id',
        'beneficiary_id',
        'operator_id',
    ];
    public function account_key() {
        return $this->belongsTo(AccountKey::class, 'account_key_id', 'id');
    }
    public function currency() {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }
    public function sender() {
        return $this->belongsTo(Sender::class, 'sender_id', 'id');
    }
    public function beneficiary() {
        return $this->belongsTo(Beneficiary::class, 'beneficiary_id', 'id');
    }
    public function operator() {
        return $this->belongsTo(Operator::class, 'operator_id', 'id');
    }
}
