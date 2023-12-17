<?php

namespace App\Http\Controllers;

use App\Models\HoltsES;
use App\Models\Produksi;
use App\Models\WinterES;
use Illuminate\Http\Request;

class PeramalanController extends Controller
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
        if($category == '10'){
            $id_categories = Produksi::KARET;
        }
        if($category == '20'){
            $id_categories = Produksi::MINYAK_SAWIT;
        }
        if($category == '30'){
            $id_categories = Produksi::BIJI_SAWIT;
        }
        if($category == '40'){
            $id_categories = Produksi::TEH;
        }
        if($category == '50'){
            $id_categories = Produksi::GULA_TEBU;
        }

        return view('perbandingan.index_perbanding',compact('category'));
    }

    public function chartPerbandingan(Request $request){

        $chartHolts = HoltsES::where('id_categories', $request->id_categories)
        ->orderBy('tahun', 'asc')->get();
        $chartWinter = WinterES::where('id_categories', $request->id_categories)
        ->orderBy('tahun', 'asc')->get();

        return response()->json([
            'status' => 200,
            'data' => [
                'holts' => $chartHolts,
                'winter' => $chartWinter,
            ]
            ]);
    }
}
