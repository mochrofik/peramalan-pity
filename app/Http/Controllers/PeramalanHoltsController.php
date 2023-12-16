<?php

namespace App\Http\Controllers;

use App\Models\AkurasiPeramalan;
use App\Models\Category;
use App\Models\HoltsES;
use App\Models\Produksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\SumSquares;

class PeramalanHoltsController extends Controller
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

        $peramalan = HoltsES::where('id_categories', $id_categories)
        ->with('category')
        ->orderBy('tahun', 'asc')
        ->get();

        $akurasi = AkurasiPeramalan::where('id_categories', $id_categories)
        ->where('tipe',1)->first();
        return view('holts_es.index_holts',compact('category', 'peramalan','akurasi'));
    }
    public function chartHolts(Request $request, $category){
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

        $peramalan = HoltsES::where('id_categories', $id_categories)
        ->with('category')
        ->orderBy('tahun', 'asc')
        ->get();
        return response()->json([
            'status'=> 200,
            'data' => $peramalan
        ]);
    }

    public function hitungHolts(Request $request, $category){

        DB::beginTransaction();
        try {
            // $category = $category;

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
    
            $index = 0;
            $peramalan = [];
            $forecast = array();
            $jumlah = array();
            $level= array();
            $trend = array();
            $tahun = array();
            $error = array();
            $error2 = array();
            $smape = array();
            $smape_untuk_upload = array();
           
    
            //Ambil nilai alpha dan beta
            $alpha = floatval($request->alpha);
            $beta = floatval($request->beta);
    
    
            //level dan trend pertama 0
            $level[0] = 0;
            $trend[0] = 0;
    
            //level dan trend kedua pakai rumus

            $level[1] =  floatval($produksi[1]->jumlah);
            $trend[1] = floatval($produksi[1]->jumlah) -  floatval($produksi[0]->jumlah);
    
            //
            foreach ($produksi as $key => $value) {
                $jumlah[$index] = floatval($value->jumlah) ;
                $tahun[$index] = $value->tahun;
                $index++;
            }
            
            for ($i=0; $i < ($jumlah_data + 3) ; $i++) { 
                $error[$i] = 0;
                $error2[$i] = 0;
                $smape_untuk_upload[$i] = 0;
                $forecast[$i] = 0;
            }
    
            for ($i=2; $i < $jumlah_data ; $i++) { 
                $level[$i] = 0;
                $trend[$i] = 0;
            }
            for ($i=2; $i < $jumlah_data ; $i++) { 
                $level[$i] =  $alpha *  floatval($produksi[$i]->jumlah) + (1 -  $alpha) *( floatval($level[$i-1]) +  floatval($trend[$i-1]));
                $trend[$i] =  $beta * (  floatval($level[$i]) -  floatval($level[$i-1]) ) + ( 1 - $beta ) *  floatval($trend[$i-1]);
                $forecast[$i] = floatval($level[$i-1]) + floatval($trend[$i-1]);
            }
        
            for ($i=$jumlah_data; $i < ($jumlah_data + 1) ; $i++) { 
                $forecast[$i] = floatval($level[$i-1]) + floatval($trend[$i-1]);
                $tahun[$i] = $tahun[$i-1]+1;
            }
            for ($i=($jumlah_data+1); $i < ($jumlah_data + 3) ; $i++) { 
                $forecast[$i] = floatval($trend[$jumlah_data-1]) + floatval($forecast[$i-1]);
                $tahun[$i] = $tahun[$i-1]+1;
            }
    
            $rmse = 0;
            for ($i=2; $i < $jumlah_data; $i++) { 
                $error[$i] = abs($produksi[$i]->jumlah - $forecast[$i]);
                $error2[$i] = abs($error[$i] - $produksi[$i]->jumlah);
                $smape[$i] = ($error[$i] / $error2[$i])*100;
                $smape_untuk_upload[$i] = ($error[$i] / $error2[$i])*100;
            }

    
            $avg_smape = array_sum($smape)/count($smape);;
            $akurasi = 100 - $avg_smape;
    
            $peramalan  = [
                'tahun' => $tahun,
                'produksi' => $jumlah,
                'level' => $level,
                'trend' => $trend,
                'forecast' => $forecast,
                'error' => $error,
                'error2' => $error2,
                'smape' => $smape,
                'smape_untuk_upload' => $smape_untuk_upload,
                'avg_smape' => $avg_smape,
                'akurasi' => $akurasi,
            ];

                        $delete =  HoltsES::
                        where('id_categories', $id_categories)->get();
                        foreach ($delete as $key => $value) {
                            $value->delete();
                        }
    
            for ($i=0; $i < ($jumlah_data+3) ; $i++) { 
    
                    $holts = new HoltsES();
                    $holts->tahun = $peramalan['tahun'][$i] ;
                    $holts->id_categories = $id_categories;
                    
                    if($i > ($jumlah_data - 1)){
                        $holts->produksi =  0; 
                        $holts->level = 0 ;
                        $holts->trend = 0 ;
                        $holts->error = 0 ;
                        $holts->error2 = 0 ;
                        $holts->smape = 0;
                    }else{
                        $holts->produksi =  $peramalan['produksi'][$i]; 
                        $holts->level = $peramalan['level'][$i] ;
                        $holts->trend = $peramalan['trend'][$i] ;
                        $holts->error = $peramalan['error'][$i] ;
                        $holts->error2 = $peramalan['error2'][$i] ;
                        $holts->smape = $peramalan['smape_untuk_upload'][$i] ;
                    }
                    $holts->forecast = $peramalan['forecast'][$i] ;
    
                    $holts->save();
            }
                    AkurasiPeramalan::where('id_categories', $id_categories)
                    ->where('tipe', 1)->delete();

                    $akurasi_peramalan = new AkurasiPeramalan();
                    $akurasi_peramalan->id_categories = $id_categories;
                    $akurasi_peramalan->tipe = 1;
                    $akurasi_peramalan->alpha = $alpha;
                    $akurasi_peramalan->beta = $beta;
                    $akurasi_peramalan->rsme = 0;
                    $akurasi_peramalan->smape = $avg_smape;
                    $akurasi_peramalan->akurasi = $akurasi;
                    $akurasi_peramalan->save();
            DB::commit();
    
            return response()->json([
                'status' => 200,
                'message' => 'Perhitungan selesai'
            ]);
           
        } catch (\Throwable $th) {
            DB::rollBack();
           return response()->json([
            'status' => 400,
            'message' => 'Erro '. $th->getMessage(). " line ". $th->getLine()
           ]);
        }

    }
    public function hitungHoltsPerKategori(Request $request, $category){

        DB::beginTransaction();
        try {
            // $category = $category;

            $id_categories = $category;
    
            if($request->alpha == '' || $request->beta == ''){
                return response()->json(['
                status' => 400,
            'mesage' => 'Lengkapi data!']);
            }
    
            $produksi = Produksi::where('id_categories', $id_categories)
            ->orderBy('tahun', 'asc')->get();
            
            $jumlah_data = count($produksi);
    
            $index = 0;
            $peramalan = [];
            $forecast = array();
            $jumlah = array();
            $level= array();
            $trend = array();
            $tahun = array();
            $error = array();
            $error2 = array();
            $smape = array();
            $smape_untuk_upload = array();
           
    
            //Ambil nilai alpha dan beta
            $alpha = $request->alpha;
            $beta = $request->beta;
    
    
            //level dan trend pertama 0
            $level[0] = 0;
            $trend[0] = 0;
    
            //level dan trend kedua pakai rumus
            $level[1] = $produksi[1]->jumlah;
            $trend[1] = ($produksi[1]->jumlah - $produksi[0]->jumlah);
    
            //
            foreach ($produksi as $key => $value) {
                $jumlah[$index] = $value->jumlah;
                $tahun[$index] = $value->tahun;
                $index++;
            }
            
            for ($i=0; $i < ($jumlah_data + 3) ; $i++) { 
                $error[$i] = 0;
                $error2[$i] = 0;
                $smape_untuk_upload[$i] = 0;
                $forecast[$i] = 0;
            }
    
            for ($i=2; $i < $jumlah_data ; $i++) { 
                $level[$i] = 0;
                $trend[$i] = 0;
            }
            for ($i=2; $i < $jumlah_data ; $i++) { 
                $level[$i] = $alpha * $produksi[$i]->jumlah + (1 - $alpha) *($level[$i-1] + $trend[$i-1]);
            }
            for ($i=2; $i < $jumlah_data ; $i++) { 
                $trend[$i] = $beta * ($level[$i] - $level[$i-1]) + (1- $beta) * $trend[$i-1];
            }
            for ($i=2; $i < $jumlah_data ; $i++) { 
                $forecast[$i] = $level[$i-1] + $trend[$i-1];
            }
    
            for ($i=$jumlah_data; $i < ($jumlah_data + 1) ; $i++) { 
                $forecast[$i] = $level[$i-1] + $trend[$i-1];
                $tahun[$i] = $tahun[$i-1]+1;
            }
            for ($i=($jumlah_data+1); $i < ($jumlah_data + 3) ; $i++) { 
                $forecast[$i] = $trend[$jumlah_data-1] + $forecast[$i-1];
                $tahun[$i] = $tahun[$i-1]+1;
            }
    
            for ($i=2; $i < $jumlah_data; $i++) { 
                $error[$i] = abs($produksi[$i]->jumlah - $forecast[$i]);
                $error2[$i] = abs($error[$i] + $produksi[$i]->jumlah);
                $smape[$i] = ($error[$i] / $error2[$i])*100;
                $smape_untuk_upload[$i] = ($error[$i] / $error2[$i])*100;
            }
    
            $avg_smape = array_sum($smape)/count($smape);;
            $akurasi = 100 - $avg_smape;
    
            $peramalan  = [
                'tahun' => $tahun,
                'produksi' => $jumlah,
                'level' => $level,
                'trend' => $trend,
                'forecast' => $forecast,
                'error' => $error,
                'error2' => $error2,
                'smape' => $smape,
                'smape_untuk_upload' => $smape_untuk_upload,
                'avg_smape' => $avg_smape,
                'akurasi' => $akurasi,
            ];


            
                        $delete =  HoltsES::
                        where('id_categories', $id_categories)->get();
                        foreach ($delete as $key => $value) {
                            $value->delete();
                        }
    
            for ($i=0; $i < ($jumlah_data+3) ; $i++) { 
    
                    $holts = new HoltsES();
                    $holts->tahun = $peramalan['tahun'][$i] ;
                    $holts->id_categories = $id_categories;
                    
                    if($i > ($jumlah_data - 1)){
                        $holts->produksi =  0; 
                        $holts->level = 0 ;
                        $holts->trend = 0 ;
                        $holts->error = 0 ;
                        $holts->error2 = 0 ;
                        $holts->smape = 0;
                    }else{
                        $holts->produksi =  $peramalan['produksi'][$i]; 
                        $holts->level = $peramalan['level'][$i] ;
                        $holts->trend = $peramalan['trend'][$i] ;
                        $holts->error = $peramalan['error'][$i] ;
                        $holts->error2 = $peramalan['error2'][$i] ;
                        $holts->smape = $peramalan['smape_untuk_upload'][$i] ;
                    }
                    $holts->forecast = $peramalan['forecast'][$i] ;
    
                    $holts->save();
                
    
            }
            DB::commit();
    
            return response()->json([
                'status' => 200,
                'message' => 'Perhitungan selesai'
            ]);
           
        } catch (\Throwable $th) {
            DB::rollBack();
           return response()->json([
            'status' => 400,
            'message' => 'Erro '. $th->getMessage(). " line ". $th->getLine()
           ]);
        }

    }
}
