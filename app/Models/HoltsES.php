<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoltsES extends Model
{
    use HasFactory;

    protected $table = 'holts_es';

    public function category(){
        return $this->hasOne(Category::class,'id', 'id_categories');
    }
    public function akurasi(){
        return $this->hasOne(AkurasiPeramalan::class,'id_categories', 'id_categories');
    }
}
