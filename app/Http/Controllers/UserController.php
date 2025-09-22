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
use Midtrans\Snap;
use Midtrans\Config;


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
                'nama'             => 'required|string|max:225',
                'tempatlahir'      => 'required|string|max:225',
                'tanggallahir'     => 'required|date',
                'alamat'           => 'required|string|max:225',
                'email'            => 'required|email|max:225',
                'nohp'             => 'required|string|max:15',
                'tanggalreservasi' => 'required|date',
                'waktureservasi'   => 'required',
                'items'            => 'nullable|string'
            ]);

            \DB::beginTransaction();
            $cartItems = [];
            $totalPreOrder = 0;
            if (!empty($request->items)) {
                $cartItems = json_decode($request->items, true);
                foreach ($cartItems as $item) {
                    $totalPreOrder += ($item['price'] * $item['quantity']);
                }
            }

            $hurufDepan = strtoupper(substr($request->nama, 0, 1));

            $lastPelanggan = \App\Models\Tpelanggan::where('kodlan', 'LIKE', "C/{$hurufDepan}%")
                ->orderBy('kodlan', 'desc')
                ->first();

            if ($lastPelanggan) {
                $lastNumber = (int) substr($lastPelanggan->kodlan, 3);
                $newNumber  = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }

            $kodeBaru = 'C/' . $hurufDepan . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

            $pelanggan = \App\Models\Tpelanggan::firstOrCreate(
                ['nowa' => $request->nohp],
                [
                    'kodlan' => $kodeBaru,
                    'nam'    => $request->nama,
                    'temlah' => $request->tempatlahir,
                    'tgllah' => $request->tanggallahir,
                    'ala'    => $request->alamat,
                    'ema'    => $request->email,
                ]
            );

            $reservasi = \App\Models\Treservasi::create([
                'pelanggan_id'     => $pelanggan->Id,
                'tanggalreservasi' => $request->tanggalreservasi,
                'waktureservasi'   => $request->waktureservasi,
                'total_preorder'   => $totalPreOrder,
                'status'           => 'pending'
            ]);

            if (!empty($cartItems)) {
                foreach ($cartItems as $item) {
                    \App\Models\Treservasi1::create([
                        'treservasi_id' => $reservasi->id,
                        'barang_id'     => $item['id'],
                        'nama_barang'   => $item['name'],
                        'satuan'        => $item['satuan'] ?? 'pcs',
                        'harga_satuan'  => $item['price'],
                        'qty'           => $item['quantity'],
                        'subtotal'      => ($item['price'] * $item['quantity'])
                    ]);
                }
            }

            \DB::commit();

            Config::$serverKey    = config('midtrans.server_key') ?: env('MIDTRANS_SERVER_KEY');
            Config::$clientKey    = config('midtrans.client_key') ?: env('MIDTRANS_CLIENT_KEY');
            Config::$isProduction = config('midtrans.is_production', false);
            Config::$isSanitized  = config('midtrans.is_sanitized', true);
            Config::$is3ds        = config('midtrans.is_3ds', true);
            \Log::debug('[MIDTRANS DEBUG] serverKey present? ' . (Config::$serverKey ? 'YES' : 'NO'));
            \Log::debug('[MIDTRANS DEBUG] clientKey present? ' . (Config::$clientKey ? 'YES' : 'NO'));
            \Log::debug('[MIDTRANS DEBUG] config(server_key): ' . (string) config('midtrans.server_key'));
            \Log::debug('[MIDTRANS DEBUG] env(MIDTRANS_SERVER_KEY): ' . (string) env('MIDTRANS_SERVER_KEY'));
            if (empty(Config::$serverKey) || empty(Config::$clientKey)) {
                \Log::error('[MIDTRANS ERROR] ServerKey/ClientKey kosong saat akan panggil Snap.');
                return redirect()->back()->with('error', 'Midtrans configuration error. Silakan hubungi admin.');
            }

            $params = [
                'transaction_details' => [
                    'order_id'     => 'RSV-' . $reservasi->id,
                    'gross_amount' => $totalPreOrder > 0 ? $totalPreOrder : 1000,
                ],
                'customer_details' => [
                    'first_name' => $request->nama,
                    'email'      => $request->email,
                    'phone'      => $request->nohp,
                ],
                'item_details' => array_map(function ($item) {
                    return [
                        'id'       => $item['id'],
                        'price'    => $item['price'],
                        'quantity' => $item['quantity'],
                        'name'     => $item['name'],
                    ];
                }, $cartItems)
            ];

            $snapToken = Snap::getSnapToken($params);
            return redirect()->route('reservasi.show', $reservasi->id)
                ->with('snapToken', $snapToken);

        } catch (\Exception $e) {
            \DB::rollback();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }


    public function showReservasi($id)
    {
        try {
            
            \Log::info('showReservasi called with ID: ' . $id);
            
            
            if (!is_numeric($id)) {
                \Log::error('Invalid ID provided: ' . $id);
                return redirect('/reservasi')
                                ->with('error', 'ID reservasi tidak valid.');
            }
            
            
            try {
                $tableExists = \Schema::hasTable('treservasi');
                \Log::info('Table treservasi exists: ' . ($tableExists ? 'yes' : 'no'));
            } catch (\Exception $e) {
                \Log::error('Error checking table existence: ' . $e->getMessage());
            }
            
            
            try {
                $reservasiSimple = \DB::table('treservasi')->where('id', $id)->first();
                \Log::info('Simple query result: ', $reservasiSimple ? ['found' => true] : ['found' => false]);
            } catch (\Exception $e) {
                \Log::error('Simple query failed: ' . $e->getMessage());
            }
            
            
            if (!class_exists('\App\Models\Treservasi')) {
                \Log::error('Model Treservasi not found');
                return redirect('/reservasi')
                                ->with('error', 'Model tidak ditemukan.');
            }
            
            
            try {
                $reservasiWithoutRelation = \App\Models\Treservasi::find($id);
                if ($reservasiWithoutRelation) {
                    \Log::info('Reservasi found without relation: ' . $reservasiWithoutRelation->id);
                } else {
                    \Log::info('Reservasi not found with ID: ' . $id);
                    return redirect('/reservasi')
                                    ->with('error', 'Reservasi tidak ditemukan.');
                }
            } catch (\Exception $e) {
                \Log::error('Error loading reservasi without relation: ' . $e->getMessage());
                throw $e;
            }
            
            
            try {
                
                $pelanggan = $reservasiWithoutRelation->pelanggan;
                \Log::info('Pelanggan loaded: ' . ($pelanggan ? 'yes' : 'no'));
            } catch (\Exception $e) {
                \Log::error('Error loading pelanggan relation: ' . $e->getMessage());
            }
            
            try {
                $details = $reservasiWithoutRelation->items;
                \Log::info('Treservasi1 loaded: ' . ($details ? count($details) . ' items' : 'no'));
            } catch (\Exception $e) {
                \Log::error('Error loading treservasi1 relation: ' . $e->getMessage());
            }
            
            
            $reservasi = \App\Models\Treservasi::with([
                'pelanggan',
                'items'
            ])->findOrFail($id);
            
            \Log::info('Reservasi loaded with relations successfully');
            
            
            $snapToken = session('snapToken');
            
            \Log::info('Show reservasi success', [
                'id' => $id,
                'status' => $reservasi->status,
                'has_snap_token' => $snapToken ? 'yes' : 'no',
                'pelanggan_name' => $reservasi->pelanggan ? $reservasi->pelanggan->nam : 'no pelanggan',
                'items_count' => $reservasi->items ? count($reservasi->items) : 0
            ]);
            
            
            if (!view()->exists('user.reservasi-show')) {
                \Log::error('View user.reservasi-show not found');
                return redirect('/reservasi')
                                ->with('error', 'Template tidak ditemukan.');
            }
            
            return view('user.reservasi-show', compact('reservasi', 'snapToken'));
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('ModelNotFoundException in showReservasi: ' . $e->getMessage());
            return redirect('/reservasi')
                            ->with('error', 'Reservasi tidak ditemukan.');
                            
        } catch (\Exception $e) {
            \Log::error('Exception in showReservasi: ', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect('/reservasi')
                            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function paymentCallback(Request $request)
    {
        try {
            $serverKey = config('midtrans.serverKey');
            $hashed = hash("sha512", $request->order_id.$request->status_code.$request->gross_amount.$serverKey);
            
            if ($hashed == $request->signature_key) {
                $orderId = $request->order_id;
                $reservasiId = str_replace('RSV-', '', $orderId);
                
                $reservasi = \App\Models\Treservasi::find($reservasiId);
                
                if ($reservasi) {
                    if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                        $reservasi->update(['status' => 'success']);
                    } elseif ($request->transaction_status == 'pending') {
                        $reservasi->update(['status' => 'pending']);
                    } elseif (in_array($request->transaction_status, ['deny', 'expire', 'cancel'])) {
                        $reservasi->update(['status' => 'failed']);
                    }
                }
            }
            
            return response()->json(['status' => 'ok']);
            
        } catch (\Exception $e) {
            \Log::error('Payment callback error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    public function paymentFinish(Request $request)
    {
        $orderId = $request->get('order_id');
        $reservasiId = str_replace('RSV-', '', $orderId);
        
        try {
            $reservasi = \App\Models\Treservasi::findOrFail($reservasiId);
            
            return redirect()->route('reservasi.show', $reservasiId)
                            ->with('success', 'Terima kasih! Pembayaran Anda sedang diproses.');
                            
        } catch (\Exception $e) {
            return redirect('/reservasi')
                            ->with('error', 'Reservasi tidak ditemukan.');
        }


    }

}