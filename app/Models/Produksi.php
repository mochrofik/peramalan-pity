<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produksi extends Model
{
    use HasFactory;

    const KARET = 1;
    const MINYAK_SAWIT = 2;
    const BIJI_SAWIT = 3;
    const TEH = 4;
    const GULA_TEBU = 5;


    public function category(){
        return $this->hasOne(Category::class, 'id', 'id_categories');
    }
}
