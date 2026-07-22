<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddressBook extends Model
{
    protected $fillable = ["name","phone","email","website","gender","age","nationality","created_by"];

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }

}
