<?php
namespace App\Http\Controllers;
use App\Models\Tsaleorder;
use App\Models\Tsaleorder1;
use App\Models\Tmeja;
use App\Models\Barang;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Container\Attributes\DB;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;


use Illuminate\Support\Facades\File;
use Carbon\Carbon;

use function PHPUnit\Framework\isEmpty;

class UserController extends Controller
{

    public function saleorder(Request $request)
    {
        $idmeja = $request->query('meja');

        if(!$idmeja){
            return redirect('pilihmeja')->with('error','Silahkan pilih meja terlebih dahulu.');
        }

        $barangs = FacadesDB::connection('maisecgc')
            ->table('tbarang')
            ->join('thargajual', 'tbarang.id', '=', 'thargajual.idbar')
            ->leftJoin('tbarangfoto', 'tbarang.id', '=', 'tbarangfoto.idbar')
            ->select(
                'tbarang.*',
                'thargajual.har as hargajual',
                'tbarangfoto.img as foto'
            )
            ->get();

        return view('user.saleorder', compact('barangs','idmeja'));
    }

    public function pilihmeja(){
        $mejas = Tmeja::all();
        return view('user.pilihmeja', compact('mejas'));
    }

    public function savesaleorder(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nohp' => 'required|string|max:20',
                'tglinput' => 'required|date',
                'items' => 'required|json',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Periksa kembali barang yang ingin dipesan.');
            }

            $items = json_decode($request->input('items'), true);

            \Log::info('Data barang yang ingin dipesan', [
                'raw_items' => $request->items,
                'decoded_items' => $items,
                'nohp' => $request->nohp,
            ]);

            if (empty($items) || !is_array($items)) {
                return redirect()->back()
                    ->withErrors(['items' => 'Tidak ada barang yang dipilih'])
                    ->withInput()
                    ->with('error', 'Periksa kembali barang yang ingin dipesan.');
            }

            FacadesDB::beginTransaction();

            $totalOrder = 0;
            foreach ($items as $item) {
                $totalOrder += $item['price'] * $item['quantity'];
            }

            $nomorSO = 'SO-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

            $saleorderId = FacadesDB::table('tsaleorder')->insertGetId([
                'noso'  => $nomorSO,
                'tgl'   => $request->input('tglinput'),
                'tot'   => $totalOrder,
                'idmej' => $request->input('idmeja'),
                'iduse' => auth()->id() ?? 1,
            ]);

            foreach ($items as $item) {
                if (
                    !isset($item['id']) ||
                    !isset($item['quantity']) ||
                    !isset($item['price']) ||
                    !isset($item['name'])
                ) {
                    throw new \Exception("Data item tidak lengkap!");
                }

                FacadesDB::table('tsaleorder1')->insert([
                    'idso'   => $saleorderId,
                    'tglinp' => $request->input('tglinput'),
                    'nam'    => $item['name'],
                    'qty'    => $item['quantity'],
                    'sat'    => $item['satuan'] ?? null,
                    'idbar'  => $item['id'],
                    'har'    => $item['price'],
                    'qtyrea' => $item['quantity'],
                    'jum'    => $item['price'] * $item['quantity'],
                ]);
            }

            FacadesDB::commit();
           
            Alert::success('Success', 'Sale Order berhasil! Nomor :' . $nomorSO);
            return redirect('listsaleorder/'.$saleorderId);



        } catch (\Exception $e) {
            FacadesDB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi Kesalahan: ' . $e->getMessage());
        }
    }

    public function listsaleorder($id)
    {
        
        $saleorder = Tsaleorder::with('salesorderdetails')->findOrFail($id);
        $saleorder = Tsaleorder::with(['salesorderdetails', 'meja'])->findOrFail($id);
        return view('user.listsaleorder', compact('saleorder'));
    }


}