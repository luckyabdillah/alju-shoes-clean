<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaction extends Model
{
    protected $guarded = ['id'];

    public function getRouteKeyName() {
        return 'uuid';
    }

    public function transaction() {
        return $this->belongsTo(Transaction::class);
    }
}
