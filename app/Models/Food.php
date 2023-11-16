<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class Food extends Model {
    use Translatable;

    protected $fillable = ['category_id'];
    public $translatedAttributes = ['name'];
    protected $table = 'foods';
    protected $casts = [
        'tags' => 'array',
    ];


    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function ingredients(){
        return $this->belongsToMany(Ingredient::class);
    }
}
