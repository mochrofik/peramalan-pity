<?php

namespace App\Http\Controllers;

use App\Models\AkurasiPeramalan;
use App\Models\Produksi;
use App\Models\WinterES;
use Illuminate\Http\Request;

class PeramalanWinterController extends Controller
{
    public function karetKering(Request $request){
        return view('produksi.karet_kering');
    }
    public function minyakSawit(Request $request){
        return view('produksi.karet_kering');
    }
    public function bijiSawit(Request $request){
        return view('produksi.karet_kering');
    }
    public function teh(Request $request){
        return view('produksi.karet_kering');
    }
    public function gulaTebu(Request $request){
        return view('produksi.karet_kering');
    }

    public function index(Request $request, $category){
        $category = $category;

        $id_categories = '';
        if($category == 'Karet Kering'){
            $id_categories = Produksi::KARET;
        }
        if($category == 'Minyak Sawit'){
            $id_categories = Produksi::MINYAK_SAWIT;
        }
        if($category == 'Biji Sawit'){
            $id_categories = Produksi::BIJI_SAWIT;
        }
        if($category == 'Teh'){
            $id_categories = Produksi::TEH;
        }
        if($category == 'Gula Tebu'){
            $id_categories = Produksi::GULA_TEBU;
        }

        $peramalan = WinterES::where('id_categories', $id_categories)
        ->with('category')
        ->orderBy('tahun', 'asc')
        ->get();

        $akurasi = AkurasiPeramalan::where('id_categories', $id_categories)
        ->where('tipe',2)->first();

        return view('holts_es.index_holts',compact('category', 'peramalan','akurasi'));
    }

    public function chartWinters(Request $request, $category){
        $category = $category;

        $id_categories = '';
        if($category == 'Karet Kering'){
            $id_categories = Produksi::KARET;
        }
        if($category == 'Minyak Sawit'){
            $id_categories = Produksi::MINYAK_SAWIT;
        }
        if($category == 'Biji Sawit'){
            $id_categories = Produksi::BIJI_SAWIT;
        }
        if($category == 'Teh'){
            $id_categories = Produksi::TEH;
        }
        if($category == 'Gula Tebu'){
            $id_categories = Produksi::GULA_TEBU;
        }

        $peramalan = WinterES::where('id_categories', $id_categories)
        ->with('category')
        ->orderBy('tahun', 'asc')
        ->get();
        return response()->json([
            'status'=> 200,
            'data' => $peramalan
        ]);
    }

    public function hitungWinter(Request $request, $category){
    
    }
}
