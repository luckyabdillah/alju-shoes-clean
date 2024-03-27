<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTreatment extends Model
{   
    protected $guarded = ['id'];

    public function getRouteKeyName() {
        return 'uuid';
    }

    public function treatment() {
        return $this->belongsTo(Treatment::class);
    }
}
