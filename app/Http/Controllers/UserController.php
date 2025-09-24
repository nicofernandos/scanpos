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
use Illuminate\Support\Facades\Log;

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

    public function midtransNotification(Request $request)
    {
        Log::info('=== MIDTRANS NOTIFICATION RECEIVED ===', [
            'timestamp' => now(),
            'ip' => request()->ip(),
            'method' => request()->method(),
            'headers' => request()->headers->all(),
            'body' => $request->all()
        ]);
        
        try {
            $notification = $request->all();
            
            $requiredFields = ['order_id', 'transaction_status', 'status_code', 'gross_amount'];
            foreach ($requiredFields as $field) {
                if (!isset($notification[$field])) {
                    Log::error('Missing required field', ['field' => $field]);
                    return response()->json(['status' => 'error'], 400);
                }
            }
        
            $serverKey = config('midtrans.server_key');
            $hashed = hash('sha512', 
                $notification['order_id'] . 
                $notification['status_code'] . 
                $notification['gross_amount'] . 
                $serverKey
            );
            
            if ($hashed !== ($notification['signature_key'] ?? '')) {
                Log::error('Invalid signature from Midtrans', [
                    'expected' => $hashed,
                    'received' => $notification['signature_key'] ?? 'missing'
                ]);
                return response()->json(['status' => 'error'], 401);
            }
            
            $orderId = $notification['order_id'];
            $transactionStatus = $notification['transaction_status'];
            
            if (strpos($orderId, 'RSV-') === 0) {
                $reservasiId = str_replace('RSV-', '', $orderId);
            } else {
                Log::error('Invalid order_id format', ['order_id' => $orderId]);
                return response()->json(['status' => 'error'], 400);
            }
            
            Log::info('Processing Midtrans notification', [
                'order_id' => $orderId,
                'reservasi_id' => $reservasiId,
                'transaction_status' => $transactionStatus
            ]);
            
            $reservasi = \App\Models\Treservasi::find($reservasiId);
            
            if (!$reservasi) {
                Log::error('Reservasi not found for notification', [
                    'reservasi_id' => $reservasiId,
                    'order_id' => $orderId
                ]);
                return response()->json(['status' => 'error'], 404);
            }
            
            $oldStatus = $reservasi->status;
            
            $statusObject = (object) ['transaction_status' => $transactionStatus];
            $this->updateTransactionStatus($reservasi, $statusObject);
            
            $newStatus = $reservasi->fresh()->status;
            
            Log::info('Status updated via notification', [
                'reservasi_id' => $reservasiId,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'midtrans_status' => $transactionStatus
            ]);
            
            // Trigger additional logic
            $this->handleStatusChangeLogic($reservasi, $newStatus);
            
            Log::info('=== MIDTRANS NOTIFICATION SUCCESS ===');
            return response()->json(['status' => 'success']);
            
        } catch (\Exception $e) {
            Log::error('=== MIDTRANS NOTIFICATION ERROR ===', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request_data' => $request->all()
            ]);
            
            return response()->json(['status' => 'error'], 500);
        }
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

            FacadesDB::beginTransaction();
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

            FacadesDB::commit();

            Config::$serverKey = Config('midtrans.server_key');
            Config::$clientKey = Config('midtrans.client_key');
            Config::$isProduction = Config('midtrans.is_production',false);
            Config::$isSanitized = Config('midtrans.is_sanitized',true);
            Config::$is3ds = Config('midtrans.is_3ds', true);
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
                    'billing_address' => [
                        'address' => $request->alamat,
                    ]
                ],
                'item_details' => array_map(function ($item) {
                    return [
                        'id'       => $item['id'],
                        'price'    => $item['price'],
                        'quantity' => $item['quantity'],
                        'name'     => $item['name'],
                    ];
                }, $cartItems),
                // TAMBAHKAN INI untuk konfigurasi callback URLs
                'callbacks' => [
                    'finish' => url('/payment/finish'), // Redirect after payment
                ]
            ];

             if (app()->environment('production') || env('NGROK_URL')) {
                    $baseUrl = env('NGROK_URL', url(''));
                    Config::$overrideNotifUrl = $baseUrl . '/midtrans/notification';
                    Log::info('Setting notification URL', ['url' => Config::$overrideNotifUrl]);
                }

            $snapToken = Snap::getSnapToken($params);

                Log::info('Snap token created', [
                    'reservasi_id' => $reservasi->id,
                    'order_id' => 'RSV-' . $reservasi->id,
                    'notification_url' => Config::$overrideNotifUrl ?? 'default'
                ]);
        

            return redirect()->route('reservasi.show', $reservasi->id)
                ->with('snapToken', $snapToken);

        } catch (\Exception $e) {
            FacadesDB::rollback();
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
                $reservasiSimple = FacadesDB::table('treservasi')->where('id', $id)->first();
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
            $serverKey = config('midtrans.server_key');
            $hashed = hash("sha512", $request->order_id.$request->status_code.$request->gross_amount.$serverKey);
            
            if ($hashed == $request->signature_key) {
                $orderId = $request->order_id;
                $reservasiId = str_replace('RSV-', '', $orderId);
                
                $reservasi = \App\Models\Treservasi::find($reservasiId);
                
                if ($reservasi) {
                    
                    $statusObject = (object) ['transaction_status' => $request->transaction_status];
                    $this->updateTransactionStatus($reservasi, $statusObject);
                    
                    
                    $this->handleStatusChangeLogic($reservasi, $reservasi->fresh()->status);
                }
            }
            
            return response()->json(['status' => 'ok']);
            
        } catch (\Exception $e) {
            Log::error('Payment callback error: ' . $e->getMessage());
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

    public function ubahStatusMidtransInternal($orderId, $status)
    {
        try {
            Log::info('Midtrans Internal Status Update', [
                'order_id' => $orderId,
                'status' => $status,
                'timestamp' => now(),
                'ip' => request()->ip()
            ]);

            
            if (strpos($orderId, 'RSV-') === 0) {
                $reservasiId = str_replace('RSV-', '', $orderId);
            } else {
                Log::error('Invalid order_id format', ['order_id' => $orderId]);
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid order_id format'
                ], 400);
            }

            
            $reservasi = \App\Models\Treservasi::find($reservasiId);

            if (!$reservasi) {
                Log::error('Reservasi not found', [
                    'order_id' => $orderId,
                    'reservasi_id' => $reservasiId
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Reservasi not found'
                ], 404);
            }

            
            $validStatuses = [
                'pending', 'settlement', 'capture', 'cancel', 
                'deny', 'expire', 'failure'
            ];

            if (!in_array($status, $validStatuses)) {
                Log::error('Invalid status from Midtrans', [
                    'order_id' => $orderId,
                    'status' => $status,
                    'valid_statuses' => $validStatuses
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status'
                ], 400);
            }

            
            $oldStatus = $reservasi->status;

            
            $statusObject = (object) ['transaction_status' => $status];
            $this->updateTransactionStatus($reservasi, $statusObject);

            
            $newStatus = $reservasi->fresh()->status;

            Log::info('Reservasi status updated successfully', [
                'order_id' => $orderId,
                'reservasi_id' => $reservasiId,
                'old_status' => $oldStatus,
                'midtrans_status' => $status,
                'new_status' => $newStatus
            ]);

            
            $this->handleStatusChangeLogic($reservasi, $newStatus);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'data' => [
                    'order_id' => $orderId,
                    'reservasi_id' => $reservasiId,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'midtrans_status' => $status
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating reservasi status', [
                'order_id' => $orderId,
                'status' => $status,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function convertMidtransStatus($midtransStatus)
    {
        switch ($midtransStatus) {
            case 'settlement':
            case 'capture':
                return 'paid'; 
            
            case 'pending':
                return 'pending';
            
            case 'expire':
            case 'cancel':
                return 'failed';
            
            case 'deny':
            case 'failure':
                return 'failed';
            
            default:
                return 'pending';
        }
    }

    private function handleStatusChangeLogic($reservasi, $newStatus)
    {
        try {
            switch ($newStatus) {
                case 'paid':
                    $this->handleSuccessfulPayment($reservasi);
                    break;
                
                case 'failed':
                    $this->handleFailedPayment($reservasi);
                    break;
                
                case 'pending':
                    $this->handlePendingPayment($reservasi);
                    break;
            }
        } catch (\Exception $e) {
            Log::error('Error in status change logic', [
                'reservasi_id' => $reservasi->id,
                'status' => $newStatus,
                'error' => $e->getMessage()
            ]);
        }
    }

    private function updateTransactionStatus($transaction, $status)
    {
        switch ($status->transaction_status) {
            case 'settlement':
                $transaction->update(['status' => 'paid']);
                break;
            case 'expire':
            case 'cancel':
                $transaction->update(['status' => 'failed']);
                break;
            case 'pending':
                
                if ($transaction->created_at < now()->subDay()) {
                    $transaction->update(['status' => 'expired']);
                } else {
                    $transaction->update(['status' => 'pending']);
                }
                break;
            case 'deny':
            case 'failure':
                $transaction->update(['status' => 'failed']);
                break;
        }
    }

    private function handleSuccessfulPayment($reservasi)
    {
        Log::info('Processing successful payment', [
            'reservasi_id' => $reservasi->id,
            'pelanggan_id' => $reservasi->pelanggan_id
        ]);

        try {    
            $pelanggan = $reservasi->pelanggan;
            
            if ($pelanggan && $pelanggan->ema) {
                
                
                Log::info('Email would be sent to: ' . $pelanggan->ema);
            }

        } catch (\Exception $e) {
            Log::error('Error in successful payment handler', [
                'reservasi_id' => $reservasi->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    private function handleFailedPayment($reservasi)
    {
        Log::info('Processing failed payment', [
            'reservasi_id' => $reservasi->id,
            'status' => $reservasi->status
        ]);
    }
    
    private function handlePendingPayment($reservasi)
    {
        Log::info('Processing pending payment', [
            'reservasi_id' => $reservasi->id
        ]);
    }

    public function checkPaymentStatus($reservasiId)
    {
        try {
            $reservasi = \App\Models\Treservasi::findOrFail($reservasiId);
            
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production', false);
            
            $orderId = 'RSV-' . $reservasi->id;
            
            $status = \Midtrans\Transaction::status($orderId);
            
            Log::info('Manual status check', [
                'order_id' => $orderId,
                'current_status' => $reservasi->status,
                'midtrans_status' => $status->transaction_status
            ]);
            
            $oldStatus = $reservasi->status;
            $this->updateTransactionStatus($reservasi, $status);
            $newStatus = $reservasi->fresh()->status;
            if ($oldStatus !== $newStatus) {
                $this->handleStatusChangeLogic($reservasi, $newStatus);
            }
            return response()->json([
                'success' => true,
                'reservasi_status' => $newStatus,
                'midtrans_status' => $status->transaction_status,
                'data' => $status
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error checking payment status', [
                'reservasi_id' => $reservasiId,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


}