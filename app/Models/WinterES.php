<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WinterES extends Model
{
    use HasFactory;

    protected $table = 'winter_es';

    public function category(){
        return $this->hasOne(Category::class,'id', 'id_categories');
    }
}
