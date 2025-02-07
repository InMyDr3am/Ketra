<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TransactionController extends Controller
{
    public function index()
    {
        // URL API yang akan diakses
        $apiUrl = "https://login-bir3msoyja-et.a.run.app"; // Ganti dengan API yang benar

        // Data yang akan dikirim dalam format JSON
        $payload = [
            'username' => 'user',
            'password' => 'password'
        ];

        // Kirim request POST ke API
        $response = Http::post($apiUrl, $payload);
        
        if ($response->successful()) {
            $json = $response->json();
    
            // Ambil dan filter data transaksi
            $transactions = collect($json['data'])
                ->filter(function ($transaction) {
                    return $transaction['detail']['transaction_status'] !== 'cancel';
                })
                ->map(function ($transaction, $order_id) {
                    return [
                        'order_id' => $order_id,
                        'product' => [
                            'device_id' => $transaction['product']['device_id'] ?? "",
                            'name' => $transaction['product']['name'] ?? "",
                            'sku' => $transaction['product']['sku'] ?? "",
                            'column' => $transaction['product']['column'] ?? "",
                            'location' => $transaction['product']['location'] ?? "",
                        ],
                        'payment' => [
                            'amount' => $transaction['payment']['amount'] ?? 0,
                            'method' => $transaction['payment']['method'] ?? "",
                            'nett' => $transaction['payment']['nett'] ?? 0,
                            'platform_fee' => $transaction['payment']['fee']['platform_sharing_revenue'] ?? 0,
                            'session_id' => $transaction['payment']['session_id'] ?? "",
                            'detail_id' => $transaction['payment']['detail']['id'] ?? "",
                            'detail_timestamp' => $transaction['payment']['detail']['ts'] ?? null
                        ],
                        'time' => [
                            'timestamp' => $transaction['time']['timestamp'] ?? null,
                            'firestore_seconds' => $transaction['time']['firestore_timestamp']['_seconds'] ?? null
                        ],
                        'transaction_status' => $transaction['detail']['transaction_status'] ?? "",
                        'refund' => [
                            'amount' => $transaction['detail']['refund_amount'] ?? 0,
                            'refund_time' => $transaction['detail']['refund_time'] ?? null
                        ],
                        'product_name' => $transaction['product']['name'] ?? "",
                        'amount' => $transaction['payment']['amount'] ?? 0
                    ];
                });

            return view('transaction.index', compact('transactions'));

        }
    
        return response()->json([
            'success' => false,
            'message' => 'Gagal mengambil data transaksi'
        ], $response->status());
        // return view('buyers.index', compact('buyers'));
    }

    public function byProduct()
    {
        // URL API yang akan diakses
        $apiUrl = "https://login-bir3msoyja-et.a.run.app"; // Ganti dengan API yang benar

        // Data yang akan dikirim dalam format JSON
        $payload = [
            'username' => 'user',
            'password' => 'password'
        ];

        // Kirim request POST ke API
        $response = Http::post($apiUrl, $payload);
        
        if ($response->successful()) {
            $json = $response->json();
    
            // Ambil dan filter data transaksi
            $transactions = collect($json['data'])
                ->filter(function ($transaction) {
                    return in_array(strtolower($transaction['detail']['transaction_status']), ['settlement', 'cancel', 'refund']);
                })
            ->map(function ($transaction, $order_id) {
                return [
                        'order_id' => $order_id,
                        'product' => [
                            'device_id' => $transaction['product']['device_id'] ?? "",
                            'name' => $transaction['product']['name'] ?? "",
                            'sku' => $transaction['product']['sku'] ?? "",
                            'column' => $transaction['product']['column'] ?? "",
                            'location' => $transaction['product']['location'] ?? "",
                        ],
                        'payment' => [
                            'amount' => $transaction['payment']['amount'] ?? 0,
                            'method' => $transaction['payment']['method'] ?? "",
                            'nett' => $transaction['payment']['nett'] ?? 0,
                            'platform_fee' => $transaction['payment']['fee']['platform_sharing_revenue'] ?? 0,
                            'session_id' => $transaction['payment']['session_id'] ?? "",
                            'detail_id' => $transaction['payment']['detail']['id'] ?? "",
                            'detail_timestamp' => $transaction['payment']['detail']['ts'] ?? null
                        ],
                        'time' => [
                            'timestamp' => $transaction['time']['timestamp'] ?? null,
                            'firestore_seconds' => $transaction['time']['firestore_timestamp']['_seconds'] ?? null
                        ],
                        // 'transaction_status' => $transaction['detail']['transaction_status'] ?? "",
                        'transaction_status' => strtolower($transaction['detail']['transaction_status']) ?? "unknown",
                        'refund' => [
                            'amount' => $transaction['detail']['refund_amount'] ?? 0,
                            'refund_time' => $transaction['detail']['refund_time'] ?? null
                        ],
                        'product_name' => $transaction['product']['name'] ?? "",
                        'amount' => $transaction['payment']['amount'] ?? 0
                    ];
                });

            // Grouping & Sum berdasarkan product_name & penjualan perstatus
            $salesSummary = $transactions
            ->groupBy(function ($item) {
                return $item['product_name'] . '|' . $item['transaction_status'];
            })
            ->map(function ($group) {
                return [
                    'product_name' => $group->first()['product_name'],
                    'transaction_status' => $group->first()['transaction_status'],
                    'total_sales' => $group->sum('amount')
                ];
            })
            ->values(); // Menghapus kunci agar lebih mudah ditampilkan di blade

            return view('transaction.by_product', compact('transactions','salesSummary'));

            //---------------------------------------
            // Olah data transaksi
            // $transactions = collect($json['data'])
            // ->filter(fn($transaction) => !empty($transaction['detail']['transaction_status']))
            // ->map(function ($transaction, $order_id) {
            //     return [
            //         'order_id' => $order_id,
            //         'product_name' => $transaction['product']['name'] ?? "Unknown Product",
            //         'amount' => $transaction['payment']['amount'] ?? 0,
            //         'transaction_status' => strtolower($transaction['detail']['transaction_status']) ?? "unknown"
            //     ];
            // });

            // Grouping & Sum berdasarkan product_name dan transaction_status
            // $salesSummary = $transactions
            // ->groupBy(['product_name', 'transaction_status'])
            // ->map(function ($groupedByStatus) {
            //     return $groupedByStatus->map(function ($group) {
            //         return [
            //             'total_sales' => $group->sum('amount')
            //         ];
            //     });
            // });

            //---------------------------------------

        }
    
        return response()->json([
            'success' => false,
            'message' => 'Gagal mengambil data transaksi'
        ], $response->status());
        // return view('buyers.index', compact('buyers'));
    }

    public function byPaymentMethod()
    {
        // URL API yang akan diakses
        $apiUrl = "https://login-bir3msoyja-et.a.run.app"; // Ganti dengan API yang benar

        // Data yang akan dikirim dalam format JSON
        $payload = [
            'username' => 'user',
            'password' => 'password'
        ];

        // Kirim request POST ke API
        $response = Http::post($apiUrl, $payload);
        
        if ($response->successful()) {
            $json = $response->json();
    
            // Ambil dan filter data transaksi
            $transactions = collect($json['data'])
                ->filter(function ($transaction) {
                    return $transaction['detail']['transaction_status'] !== 'cancel';
                })
                ->map(function ($transaction, $order_id) {
                    return [
                        'order_id' => $order_id,
                        'product' => [
                            'device_id' => $transaction['product']['device_id'] ?? "",
                            'name' => $transaction['product']['name'] ?? "",
                            'sku' => $transaction['product']['sku'] ?? "",
                            'column' => $transaction['product']['column'] ?? "",
                            'location' => $transaction['product']['location'] ?? "",
                        ],
                        'payment' => [
                            'amount' => $transaction['payment']['amount'] ?? 0,
                            'method' => $transaction['payment']['method'] ?? "",
                            'nett' => $transaction['payment']['nett'] ?? 0,
                            'platform_fee' => $transaction['payment']['fee']['platform_sharing_revenue'] ?? 0,
                            'session_id' => $transaction['payment']['session_id'] ?? "",
                            'detail_id' => $transaction['payment']['detail']['id'] ?? "",
                            'detail_timestamp' => $transaction['payment']['detail']['ts'] ?? null
                        ],
                        'time' => [
                            'timestamp' => $transaction['time']['timestamp'] ?? null,
                            'firestore_seconds' => $transaction['time']['firestore_timestamp']['_seconds'] ?? null
                        ],
                        'transaction_status' => $transaction['detail']['transaction_status'] ?? "",
                        'refund' => [
                            'amount' => $transaction['detail']['refund_amount'] ?? 0,
                            'refund_time' => $transaction['detail']['refund_time'] ?? null
                        ],
                        'payment_method' => $transaction['payment']['method'] ?? "",
                        'amount' => $transaction['payment']['amount'] ?? 0
                    ];
                });

            // Grouping & Sum berdasarkan product_name
            $salesSummary = $transactions
                ->groupBy('payment_method') // Mengelompokkan berdasarkan nama produk
                ->map(function ($group) {
                    return [
                        'payment_method' => $group->first()['payment_method'],
                        'total_sales' => $group->sum('amount') // Menjumlahkan total amount
                    ];
                })->values();
    
            // return response()->json([
            //     'success' => true,
            //     'message' => $json['message'],
            //     'data' => $transactions->values()
            // ], 200, [], JSON_PRETTY_PRINT);

            return view('transaction.by_payment_method', compact('transactions','salesSummary'));

        }
    
        return response()->json([
            'success' => false,
            'message' => 'Gagal mengambil data transaksi'
        ], $response->status());
        // return view('buyers.index', compact('buyers'));
    }
}
