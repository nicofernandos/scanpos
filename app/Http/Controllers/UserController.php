<?php
namespace App\Http\Controllers;
use App\Models\Tsaleorder;
use App\Models\Tsaleorder1;
use App\Models\Tmeja;
use App\Models\Barang;
use App\Models\Tpelanggan;
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
            Alert::error('Error', 'Silahkan pilih meja terlebih dahulu !');
            return redirect('pilihmeja');
        }

        $meja = Tmeja::find($idmeja);
        if(!$meja){
            Alert::error('Error','Meja tidak di temukan !');
            return redirect('pilihmeja');
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

    public function cekpelanggan(Request $request)
    {
        $pelanggan = Tpelanggan::where('nowa',$request->nohp)->first();

        if($pelanggan) {
            return response()->json(
                [
                    'status' => 'found',
                    'nama' => $pelanggan->nam,
                    'alamat' => $pelanggan->ala,
                    'email'=> $pelanggan->ema,
                    'tempatlahir'=> $pelanggan->temlah,
                    'tanggallahir'=> $pelanggan->tgllah,
                ]
                );
        } else {
            return response()->json(['status' => 'not found']);
        }


    }

    public function reservasi()
    {
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
        return view('user.reservasi',compact('barangs'));
    }

    public function savereservasi(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|string|max:225',
                'tempatlahir' => 'required|string|max:225',
                'tanggallahir' => 'required|date',
                'alamat' => 'required|string|max:225',
                'email' => 'required|email|max:225',
                'nohp' => 'required|string|max:15',
                'tanggalreservasi' => 'required|date',
                'waktureservasi' => 'required',
                'items' => 'nullable|string'
            ]);

            \DB::beginTransaction();

            $cartItems = [];
            $totalPreOrder = 0;
            
                
            if (!empty($request->items)) {
                $cartItems = json_decode($request->items, true);
                    
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception('Format data items tidak valid');
                }

                dd($cartItems);
                    
                foreach ($cartItems as $item) {
                    if (!isset($item['price']) || !isset($item['quantity'])) {
                        throw new \Exception('Data item tidak lengkap');
                    }
                    $totalPreOrder += ($item['price'] * $item['quantity']);
                }
            }

            $reservasi = \App\Models\Treservasi::create([
                'nama' => $request->nama,
                'tempatlahir' => $request->tempatlahir,
                'tanggallahir' => $request->tanggallahir,
                'alamat' => $request->alamat,
                'email' => $request->email,
                'nohp' => $request->nohp,
                'tanggalreservasi' => $request->tanggalreservasi,
                'waktureservasi' => $request->waktureservasi,
                'total_preorder' => $totalPreOrder,
                'status' => 'pending'
            ]);

            if (!empty($cartItems)) {
                foreach ($cartItems as $item) {
                    \App\Models\TreservasiItem::create([
                        'treservasi_id' => $reservasi->id,
                        'barang_id' => $item['id'],
                        'nama_barang' => $item['name'],
                        'satuan' => $item['satuan'] ?? 'pcs',
                        'harga_satuan' => $item['price'],
                        'qty' => $item['quantity'],
                        'subtotal' => ($item['price'] * $item['quantity'])
                    ]);
                }
            }

            \DB::commit();

            $successMessage = 'Berhasil Reservasi atas nama : ' . $request->nama;
            if ($totalPreOrder > 0) {
                $successMessage .= ' dengan pre-order senilai Rp ' . number_format($totalPreOrder, 0, ',', '.') . 
                                    ' (' . array_sum(array_column($cartItems, 'quantity')) . ' item)';
            }

            Alert::success('Success', $successMessage);
            return redirect()->back();

        } catch (\Exception $e) {
            \DB::rollback();
                
            Alert::error('Error', 'Gagal reservasi: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }



}