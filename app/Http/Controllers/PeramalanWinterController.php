<?php

namespace App\Http\Controllers;

use App\Models\AkurasiPeramalan;
use App\Models\Produksi;
use App\Models\WinterES;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        return view('winter_es.index_winter_es',compact('category', 'peramalan','akurasi'));
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
        DB::beginTransaction();
        try {
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
    
            if($request->alpha == '' || $request->beta == ''){
                return response()->json(['
                status' => 400,
            'mesage' => 'Lengkapi data!']);
            }

            $produksi = Produksi::where('id_categories', $id_categories)
            ->orderBy('tahun', 'asc')->get();

            $jumlah_data = count($produksi);
            //Ambil nilai alpha dan beta
            $alpha = floatval($request->alpha);
            $beta = floatval($request->beta);
    
            $forecast = array();
            $data_produksi = array();
            $xt = array();
            $st = array();
            $bt = array();
            $lmt_l = array();
            $lmt = array();

            if($jumlah_data > 10){

                for ($i=0; $i < $jumlah_data; $i++) { 
                    $data_produksi[$i] = $produksi[$i]->jumlah;
                    $st[$i] = 0;
                    $bt[$i] = 0;
                    $lmt_l[$i] =0 ;
                }
                for ($i=0; $i < ($jumlah_data + 3); $i++) { 
                    $lmt[$i] = 0;
                }
                for ($i=0; $i < 3; $i++) { 
                    //Nilai Xt awal
                    $xt[$i] = floatval($produksi[$i+3]->jumlah) -  floatval($produksi[$i]->jumlah);
                }
                $st[2] =($produksi[0]->jumlah + $produksi[1]->jumlah + $produksi[2]->jumlah) / 3;
                $bt[2] = ($xt[0]+$xt[1]+$xt[2])/pow(3,2);

                for ($i=3; $i < $jumlah_data; $i++) { 
                    $st[$i] = $alpha * ($produksi[$i]->jumlah);
                }
                for ($i=0; $i < 3; $i++) { 
                    $lmt[$i] =  ($produksi[$i]->jumlah - $st[2]);
                }
               
                for ($i=3; $i < $jumlah_data; $i++) { 
                    $lmt_l[$i] =  $lmt[$i-3];
                }
                for ($i=3; $i < $jumlah_data; $i++) { 
                    $st[$i] = $alpha * ($produksi[$i]->jumlah - $lmt_l[$i]) + (1 - $alpha) * ($st[$i-1] + $bt[$i-1]);
                    $bt[$i] = $beta * ($produksi[$i]->jumlah - $produksi[$i-1]->jumlah) + (1 - $beta) * $bt[$i-1];
                }

                return $result = [
                    'produksi' => $data_produksi,
                    'xt' => $xt,
                    'st' => $st,
                    'bt' => $bt,
                    'lmt' => $lmt,
                    'lmt_l' => $lmt_l,
                ];

            }else{
                return response()->json([
                    'status' => 400,
                    'message' => 'Data kurang dari 10'
                ]);
            }



        } catch (\Throwable $th) {

            throw $th;
        }


    }
}
