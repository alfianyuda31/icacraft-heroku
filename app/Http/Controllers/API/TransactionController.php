<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\transaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;

class TransactionController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 6);
        $craft_id = $request->input('craft_id');
        $status = $request->input('status');


        //untuk mengambil id craft
        if($id)
        {
            $transaction = transaction::with(['craft','user'])->find($id);

            if($transaction)
            {
                return ResponseFormatter::success(
                    $transaction,
                    'Data transaksi berhasil diambil' 
                );
            }
            else
            {
                return ResponseFormatter::error(
                    null,
                    'Data transaksi tidak ada',
                    404
                ); 
            }
        }

        //query untuk mengambil data craft
        $transaction = transaction::with(['craft', 'user'])->where('user_id', Auth::user()->id);

        if($craft_id)
        {
            $transaction->where('craft_id',$craft_id);
        }

        if($status)
        {
            $transaction->where('status',$status);
        }

        //pengembalian data yang telah diambil
        return ResponseFormatter::success(
            $transaction->paginate($limit),
            'Data list transaksi berhasil diambil'
        );


    }

    //fungsi update transaksi
    public function update(Request $request, $id)
    {
        //mengambil data 
        $transaction = transaction::findOrFail($id);
        
        //mengupdate data
        $transaction->update($request->all());

        //mengembalikan data yang telah di update
        return ResponseFormatter::success($transaction, 'Transaksi berhasil diperbarui');
        
    }

    public function checkout(Request $request)
    {
        //Validasi request dari front end
        $request->validate([
            'craft_id' => 'required|exists:craft,id',
            'user_id' => 'required|exists:users,id',
            'quantity' => 'required',
            'total' => 'required',
            'status' => 'required',
        ]);

        $transaction = transaction::create([
            'craft_id' => $request->craft_id,
            'user_id' => $request->user_id,
            'quantity' => $request->quantity,
            'total' => $request->total,
            'status' => $request->status,
            'payment_url' => '',
        ]);

        //Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');

        //Panggil transaksi yang tadi dibuat
        $transaction = transaction::with(['craft', 'user'])->find($transaction->id);

        //Membuat Transaksi Midtrans
        $midtrans = [
            'transaction_details' => [
                'order_id' => $transaction->id,
                'gross_amount' => (int) $transaction->total,
            ],
            'customer_details' => [
                'first_name' => $transaction->user->name,
                'email' => $transaction->user->email,
            ],
            'enabled_payment' => ['gopay','bank_transfer'],
            'vtweb' => []
        ];

        //Memanggil Midtrans

        try {
            //Ambil halaman payment midtrans
            $paymentUrl = Snap::createTransaction($midtrans)->redirect_url;
            
            $transaction->payment_url = $paymentUrl;
            $transaction->save();
            
            //Mengembalikan Data ke API
            return ResponseFormatter::success($transaction,'Transaksi Berhasil');
        
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 'Transaksi Gagal');
        }
    }
}
