<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $guarded = ['id'];

    public function getRouteKeyName() {
        return 'uuid';
    }

    public function transaction() {
        return $this->belongsTo(Transaction::class);
    }

    public function treatment() {
        return $this->belongsTo(Treatment::class);
    }

    public function treatment_details() {
        return $this->belongsTo(TreatmentDetail::class);
    }
}
