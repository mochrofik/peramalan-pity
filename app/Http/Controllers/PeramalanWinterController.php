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

        $id_categories = $category;
        if($category == '1'){
            $id_categories = Produksi::KARET;
        }
        if($category == '2'){
            $id_categories = Produksi::MINYAK_SAWIT;
        }
        if($category == '3'){
            $id_categories = Produksi::BIJI_SAWIT;
        }
        if($category == '4'){
            $id_categories = Produksi::TEH;
        }
        if($category == '5'){
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
        if($category == '1'){
            $id_categories = Produksi::KARET;
        }
        if($category == '2'){
            $id_categories = Produksi::MINYAK_SAWIT;
        }
        if($category == '3'){
            $id_categories = Produksi::BIJI_SAWIT;
        }
        if($category == '4'){
            $id_categories = Produksi::TEH;
        }
        if($category == '5'){
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
            $id_categories = $category;
    
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
            $gamma = floatval($request->gamma);
    
            $forecast = array();
            $data_produksi = array();
            $xt = array();
            $st = array();
            $bt = array();
            $lmt_l = array();
            $lmt = array();
            $error = array();
            $error2 = array();
            $smape = array();
            $tahun = array();

            if($jumlah_data > 10){

                for ($i=0; $i < $jumlah_data; $i++) { 
                    $data_produksi[$i] = $produksi[$i]->jumlah;
                    $st[$i] = 0;
                    $bt[$i] = 0;
                    $lmt[$i] = 0;
                    $tahun[$i] = $produksi[$i]->tahun;
                }
                for ($i=0; $i < ($jumlah_data + 3); $i++) { 
                    $lmt_l[$i] =0 ;
                    $forecast[$i] = 0;
                    $smape_untuk_upload[$i] = 0;
                    $error[$i] = 0;
                    $error2[$i] = 0;
                    $xt[$i] = 0;
                }
                for ($i=0; $i < 3; $i++) { 
                    //Nilai Xt awal
                    $temp = $produksi[$i+3]->jumlah  - $produksi[$i]->jumlah ;
                    $xt[$i] =  floatval(number_format($temp, 3));
                }
                $st[2] = floatval( number_format( ($produksi[0]->jumlah + $produksi[1]->jumlah + $produksi[2]->jumlah) / 3 , 3 )) ;
                $bt[2] =  ($xt[0] + $xt[1] +$xt[2])/pow(3,2);

                for ($i=0; $i < 3; $i++) { 
                    $lmt[$i] =  floatval(number_format($produksi[$i]->jumlah - $st[2], 3));
                }
                for ($i=3; $i < $jumlah_data; $i++) { 
                    $lmt_l[$i] =  floatval( number_format($lmt[$i-3], 3) );
                    $st[$i] = floatval(number_format($alpha * ($produksi[$i]->jumlah - $lmt_l[$i]) + (1-$alpha) * ($st[$i-1] + $bt[$i-1]) , 3));
                    $bt[$i] = floatval( number_format($beta * ($st[$i] - $st[$i-1]) + (1 - $beta) * $bt[$i-1] , 3) )  ;
                    $lmt[$i] =  floatval(number_format($gamma * ($produksi[$i]->jumlah - $st[$i]) + (1-$gamma) * $lmt_l[$i] ,3));
                }
                
                for ($i=3; $i < ($jumlah_data) ; $i++) { 
                    $forecast[$i] = floatval(number_format( $st[$i-1] + $bt[$i-1] + $lmt_l[$i]  ,3));
                }
                for ($i=$jumlah_data; $i < ($jumlah_data + 3); $i++) { 
                    $lmt_l[$i] =  floatval( number_format($lmt[$i-3] ,3) );
                    $forecast[$i] = floatval(number_format( $st[$jumlah_data-1] + $bt[$jumlah_data-1] * 1 + $lmt_l[$i]  ,3));
                    $tahun[$i] = $tahun[$i-1] + 1;
                }

                for ($i=3; $i < ($jumlah_data) ; $i++) { 
                    $temp = abs($forecast[$i] - $produksi[$i]->jumlah) ;
                    $error[$i] = floatval(number_format($temp, 3));
                    $error2[$i] = abs($forecast[$i] + $produksi[$i]->jumlah);
                }
                for ($i=3; $i < ($jumlah_data) ; $i++) { 
                $smape[$i] =  ($error[$i] / $error2[$i])*100 ;
                $smape_untuk_upload[$i] = floatval(number_format( ($error[$i] / $error2[$i])*100 ,3));
                }

                $avg_smape = array_sum($smape)/count($smape);
                $akurasi = 100 - $avg_smape;
               

                 $result = [
                    'avg_smape' => floatval(number_format($avg_smape, 3)),
                    'akurasi' => floatval(number_format($akurasi, 3)),
                    'tahun' => $tahun,
                    'produksi' => $data_produksi,
                    'xt' => $xt,
                    'st' => $st,
                    'bt' => $bt,
                    'lmt' => $lmt,
                    'lmt_l' => $lmt_l,
                    'forecast' => $forecast,
                    'error' => $error,
                    'error2' => $error2,
                    'smape' => $smape_untuk_upload,
                ];

                $delete  = WinterES::where('id_categories', $id_categories)->get();
                foreach ($delete as $key => $value) {
                    $value->delete();
                }

                for ($i=0; $i < ($jumlah_data + 3) ; $i++) { 
                    $winter = new WinterES();
                    $winter->tahun = $result['tahun'][$i];
                    $winter->id_categories = $id_categories;
                    if($i > ($jumlah_data - 1)){
                        $winter->produksi = 0;;
                        $winter->st = 0;
                        $winter->bt = 0;
                        $winter->lmt = 0;
                        $winter->error = 0;
                        $winter->error2 = 0;
                        $winter->smape = 0;
                    }else{
                        $winter->produksi = $result['produksi'][$i];
                        $winter->st = $result['st'][$i];
                        $winter->bt = $result['bt'][$i];
                        $winter->lmt = $result['lmt'][$i];
                        $winter->error = $result['error'][$i];
                        $winter->error2 = $result['error2'][$i];
                        $winter->smape = $result['smape'][$i];
                    }
                    $winter->lmt_l = $result['lmt_l'][$i];
                    $winter->forecast = $result['forecast'][$i];
                    $winter->xt = $result['xt'][$i];

                    $winter->save();
                }

                AkurasiPeramalan::where('id_categories', $id_categories)
                ->where('tipe', 2)->delete();

                $akurasi_peramalan = new AkurasiPeramalan();
                $akurasi_peramalan->id_categories = $id_categories;
                $akurasi_peramalan->tipe = 2;
                $akurasi_peramalan->alpha = $alpha ;
                $akurasi_peramalan->beta = $beta ;
                $akurasi_peramalan->gamma = $gamma ;
                $akurasi_peramalan->rsme = 0 ;
                $akurasi_peramalan->smape = $avg_smape ;
                $akurasi_peramalan->akurasi = $akurasi ;

                $akurasi_peramalan->save();

                DB::commit();

                return response()->json([
                    'status' => 200,
                    'message' => 'Perhitungan selesai'
                ]);

            }else{
                return response()->json([
                    'status' => 400,
                    'message' => 'Data kurang dari 10'
                ]);
            }



        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
             'status' => 400,
             'message' => 'Erro '. $th->getMessage(). " line ". $th->getLine()
            ]);
        }


    }
}
