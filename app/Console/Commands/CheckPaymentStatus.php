<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Treservasi;
use Midtrans\Config;
use Midtrans\Transaction as MidtransTransaction;

class CheckPaymentStatus extends Command
{
    protected $signature = 'payment:check-status';
    protected $description = 'Check pending payment status from Midtrans';

    public function handle()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        $this->info('Starting payment status check...');
        $pendingTransactions = Treservasi::where('status', 'pending')
            // ->where('created_at', '<', now()->subHour())
            ->get();

        $this->info("Found {$pendingTransactions->count()} pending transactions to check");

        foreach ($pendingTransactions as $transaction) {
            try {
                $midtransOrderId = 'RSV-' . $transaction->id;

                $status = MidtransTransaction::status($midtransOrderId);
                $this->updateTransactionStatus($transaction, $status);

                $this->info("Updated transaction: RSV-{$midtransOrderId}");
                
            } catch (\Exception $e) {
                $this->error("Error checking transaction  RSV-{$transaction->id}: " . $e->getMessage());
            }
        }



        $this->info("Payment status check completed.");
        return 0;
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
                }
                break;
        }
    }
}