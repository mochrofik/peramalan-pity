<?php

namespace App\Http\Controllers;

use App\Models\Produksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProduksiController extends Controller
{
    public function karetKering(Request $request){

        $produksi = Produksi::where('id_categories', Produksi::KARET)
        ->with('category')
        ->orderBy('tahun', 'asc')
        ->get();
        return view('produksi.karet_kering', compact('produksi'));
    }
    public function minyakSawit(Request $request){
        $produksi = Produksi::where('id_categories', Produksi::MINYAK_SAWIT)
        ->with('category')
        ->get();
        return view('produksi.minyak_sawit', compact('produksi'));
    }
    public function bijiSawit(Request $request){
        $produksi = Produksi::where('id_categories', Produksi::BIJI_SAWIT)
        ->with('category')
        ->orderBy('tahun', 'asc')
        ->get();
        return view('produksi.biji_sawit', compact('produksi'));
    }
    public function teh(Request $request){
        $produksi = Produksi::where('id_categories', Produksi::TEH)
        ->with('category')
        ->orderBy('tahun', 'asc')
        ->get();
        return view('produksi.teh', compact('produksi'));
    }
    public function gulaTebu(Request $request){
        $produksi = Produksi::where('id_categories', Produksi::GULA_TEBU)
        ->with('category')
        ->orderBy('tahun', 'asc')
        ->get();
        return view('produksi.gula_tebu', compact('produksi'));
    }

    public function actionImport(Request $request){
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $inputFileType = 'Xlsx';
        $inputFileName = $request->file;
        $reader = IOFactory::createReader($inputFileType);
        $spreadsheet = $reader->load($inputFileName);

        $sheetKaret = $spreadsheet->getSheetByName('Karet Kering');
        $hightKaret = $sheetKaret->getHighestRow();

        $minyak = $spreadsheet->getSheetByName('Minyak Sawit');
        $hightMinyak = $minyak->getHighestRow();

        $sheetBijiSawit = $spreadsheet->getSheetByName('Biji Sawit');
        $hightBijiSawit = $sheetBijiSawit->getHighestRow();

        $sheetTeh = $spreadsheet->getSheetByName('Teh');
        $hightTeh = $sheetTeh->getHighestRow();

        $sheetGulaTebu = $spreadsheet->getSheetByName('Gula Tebu');
        $hightGulaTebu = $sheetGulaTebu->getHighestRow();

        $message = 'Berhasil upload data Laporan Produksi'; 
        DB::beginTransaction();


        try {

            for ($i=3; $i < ($hightKaret + 1) ; $i++) { 
                 $tahun = $sheetKaret->getCellByColumnAndRow(1, $i)->getCalculatedValue();
                 $jumlah = $sheetKaret->getCellByColumnAndRow(2, $i)->getCalculatedValue();

                 if($tahun != null || $jumlah != null){
                     $produksi = Produksi::where('tahun', $tahun )->where( 'id_categories', Produksi::KARET)->first();
    
                     if($produksi != null){
                         $produksi->tahun = $tahun;
                         $produksi->jumlah = $jumlah;
                         $produksi->id_categories = 1;
                    }else{
                         $produksi = new Produksi();
                         $produksi->tahun = $tahun;
                         $produksi->jumlah = $jumlah;
                         $produksi->id_categories = 1;
                     }
    
                    $produksi->save();

                 }

            }
            for ($i=3; $i < ($hightMinyak + 1) ; $i++) { 
                 $tahun = $minyak->getCellByColumnAndRow(1, $i)->getCalculatedValue();
                 $jumlah = $minyak->getCellByColumnAndRow(2, $i)->getCalculatedValue();

                 if($tahun != null || $jumlah != null){
                     $produksi = Produksi::where('tahun', $tahun)->where('id_categories', Produksi::MINYAK_SAWIT)->first();
    
                     if($produksi != null){
                         $produksi->tahun = $tahun;
                         $produksi->jumlah = $jumlah;
                         $produksi->id_categories = Produksi::MINYAK_SAWIT;
                    }else{
                         $produksi = new Produksi();
                         $produksi->tahun = $tahun;
                         $produksi->jumlah = $jumlah;
                         $produksi->id_categories = Produksi::MINYAK_SAWIT;
                     }
    
                    $produksi->save();
                 }

            }
            for ($i=3; $i < ($hightBijiSawit + 1) ; $i++) { 
                 $tahun = $sheetBijiSawit->getCellByColumnAndRow(1, $i)->getCalculatedValue();
                 $jumlah = $sheetBijiSawit->getCellByColumnAndRow(2, $i)->getCalculatedValue();

                 if($tahun == null || $jumlah == null){
                 }else{
                     $produksi = Produksi::where('tahun', $tahun)->where('id_categories', Produksi::BIJI_SAWIT)->first();
    
                     if($produksi != null){
                         $produksi->tahun = $tahun;
                         $produksi->jumlah = $jumlah;
                         $produksi->id_categories = Produksi::BIJI_SAWIT;
                    }else{
                         $produksi = new Produksi();
                         $produksi->tahun = $tahun;
                         $produksi->jumlah = $jumlah;
                         $produksi->id_categories = Produksi::BIJI_SAWIT;
                     }
    
                    $produksi->save();
                 }

            }
            for ($i=3; $i < ($hightTeh + 1) ; $i++) { 
                 $tahun = $sheetTeh->getCellByColumnAndRow(1, $i)->getCalculatedValue();
                 $jumlah = $sheetTeh->getCellByColumnAndRow(2, $i)->getCalculatedValue();

                 if($tahun != null || $jumlah != null){
                     $produksi = Produksi::where('tahun', $tahun)->where( 'id_categories', Produksi::TEH)->first();
    
                     if($produksi != null){
                         $produksi->tahun = $tahun;
                         $produksi->jumlah = $jumlah;
                         $produksi->id_categories = Produksi::TEH;
                    }else{
                         $produksi = new Produksi();
                         $produksi->tahun = $tahun;
                         $produksi->jumlah = $jumlah;
                         $produksi->id_categories = Produksi::TEH;
                     }
    
                    $produksi->save();
                 
                }

            }
            for ($i=3; $i < ($hightGulaTebu + 1) ; $i++) { 
                 $tahun = $sheetGulaTebu->getCellByColumnAndRow(1, $i)->getCalculatedValue();
                 $jumlah = $sheetGulaTebu->getCellByColumnAndRow(2, $i)->getCalculatedValue();
                 if($tahun != null || $jumlah != null){
                     $produksi = Produksi::where('tahun', $tahun)->where( 'id_categories', Produksi::GULA_TEBU)->first();
    
                     if($produksi != null){
                         $produksi->tahun = $tahun;
                         $produksi->jumlah = $jumlah;
                         $produksi->id_categories = Produksi::GULA_TEBU;
                    }else{
                         $produksi = new Produksi();
                         $produksi->tahun = $tahun;
                         $produksi->jumlah = $jumlah;
                         $produksi->id_categories = Produksi::GULA_TEBU;
                     }
    
                    $produksi->save();
                 
                }

            }
                
            DB::commit();

            Session::flash('success', 'Berhasil Import data!'); 
            
            return redirect('produksi/import');
            
        } catch (\Throwable $th) {
            DB::rollBack();
            Session::flash('error', 'Gagal import! ' . $th->getMessage()); 
            throw $th;
        }

    }

    public function import(Request $request){
        return view('produksi.import');
    }

    public function editProduksi(Request $request){
        DB::beginTransaction();
        try {
            if($request->tahun == '' || $request->jumlah == ''){
                return response()->json([
                    'status' => 400,
                    'message' => 'Lengkapi data'
                ]);
            }
    
            $produksi = Produksi::where('id', $request->id)->first();
            if($produksi != null){
                if($produksi->tahun != $request->tahun){
                    $cek = Produksi::where('id_categories', $request->id_categories)
                    ->where('tahun', $request->tahun)->count();
                    if($cek > 0){
                        return response()->json([
                            'status' => 400,
                            'message' => 'Data produksi tahun '.$request->tahun. " sudah ada!"
                        ]);
                    }
    
                }else{
                    $produksi->tahun = $request->tahun;
                    $produksi->jumlah = $request->jumlah;
                    $produksi->save();
                }
            }else{
                return response()->json([
                    'status' => 400,
                    'message' => 'Data produksi tidak ada!'
                ]);
            }
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Data berhasil diedit!'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 400,
                'message' => 'Error '.$th->getMessage()
            ]);
        }
       
    }
    public function delete($id){
        DB::beginTransaction();
        try {
             Produksi::where('id', $id)->delete();   
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil menghapus data'
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => 'Error '.$th->getMessage()
            ]);
        }
    }
    public function tambahProduksi(Request $request){
        DB::beginTransaction();
        try {
            if($request->tahun == '' || $request->jumlah == ''){
                return response()->json([
                    'status' => 400,
                    'message' => 'Lengkapi data'
                ]);
            }
    
            $produksi = Produksi::where('id', $request->id)->first();
            if($produksi != null){
                if($produksi->tahun != $request->tahun){
                    $cek = Produksi::where('id_categories', $request->id_categories)
                    ->where('tahun', $request->tahun)->count();
                    if($cek > 0){
                        return response()->json([
                            'status' => 400,
                            'message' => 'Data produksi tahun '.$request->tahun. " sudah ada!"
                        ]);
                    }
    
                }else{
                    $produksi->tahun = $request->tahun;
                    $produksi->jumlah = $request->jumlah;
                    $produksi->save();
                }
            }else{
                return response()->json([
                    'status' => 400,
                    'message' => 'Data produksi tidak ada!'
                ]);
            }
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Data berhasil diedit!'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 400,
                'message' => 'Error '.$th->getMessage()
            ]);
        }
       
    }
}
