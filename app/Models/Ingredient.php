<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model {
    protected $fillable = ['name'];

    public function foods(){
        return $this->belongsToMany(Food::class);
    }
}
