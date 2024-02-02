<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = ['id'];

    public function getRouteKeyName() {
        return 'uuid';
    }

    public function detail_transactions() {
        return $this->hasMany(DetailTransaction::class);
    }

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function outlet() {
        return $this->belongsTo(Outlet::class);
    }
}
