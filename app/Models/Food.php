<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class Food extends Model {
    use Translatable;

    protected $fillable = ['category_id'];
    public $translatedAttributes = ['name'];


    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function tags(){
        return $this->belongsToMany(Tag::class);
    }

    public function ingredients(){
        return $this->belongsToMany(Ingredient::class);
    }
}
