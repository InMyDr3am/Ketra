<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;

class TransactionController extends Controller
{
    public function index(Request $request)
    {

        $apiUrl = "https://login-bir3msoyja-et.a.run.app"; 
        $payload = [
            'username' => 'user',
            'password' => 'password'
        ];
        $response = Http::post($apiUrl, $payload);
        
        if ($response->successful()) {
            $json = $response->json();
    
            // Ambil dan filter data transaksi
            $transactions = collect($json['data'])
                // ->filter(function ($transaction) {
                //     return $transaction['detail']['transaction_status'] !== 'cancel';
                // })
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

                $perPage = 20;
                $currentPage = LengthAwarePaginator::resolveCurrentPage();
                $currentItems = $transactions->slice(($currentPage - 1) * $perPage, $perPage)->values();
                $paginatedTransactions = new LengthAwarePaginator($currentItems, $transactions->count(), $perPage);
                $paginatedTransactions->setPath($request->url());

            return view('transaction.index', compact('paginatedTransactions'));

        }
    
        return response()->json([
            'success' => false,
            'message' => 'Gagal mengambil data transaksi'
        ], $response->status());
    }

    public function byProduct(Request $request)
    {
        
        $apiUrl = "https://login-bir3msoyja-et.a.run.app"; 
        $payload = [
            'username' => 'user',
            'password' => 'password'
        ];
        $response = Http::post($apiUrl, $payload);
        
        if ($response->successful()) {
            $json = $response->json();
    
            // Ambil dan filter data transaksi
            $transactions = collect($json['data'])
                // ->filter(function ($transaction) {
                //     return in_array(strtolower($transaction['detail']['transaction_status']), 
                //         ['settlement', 'cancel', 'refund']);
                // })
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
                ->values();

                $perPage = 20;
                $currentPage = LengthAwarePaginator::resolveCurrentPage();
                $currentItems = $salesSummary->slice(($currentPage - 1) * $perPage, $perPage)->values();
                $paginatedTransactions = new LengthAwarePaginator($currentItems, $salesSummary->count(), $perPage);
                $paginatedTransactions->setPath($request->url());

            return view('transaction.by_product', compact('paginatedTransactions'));

        }
    
        return response()->json([
            'success' => false,
            'message' => 'Gagal mengambil data transaksi'
        ], $response->status());

    }

    public function transactionDetail(Request $request)
    {
        $productName = $request->query('product_name');
        $transactionStatus = $request->query('transaction_status');

        $apiUrl = "https://login-bir3msoyja-et.a.run.app";
        $payload = [
            'username' => 'user',
            'password' => 'password'
        ];
        $response = Http::post($apiUrl, $payload);

        if ($response->successful()) {
            $json = $response->json();

            // Ambil dan filter data transaksi sesuai dengan produk dan status yang dipilih
            $transactions = collect($json['data'])
                ->filter(function ($transaction) use ($productName, $transactionStatus) {
                    return strtolower($transaction['product']['name'] ?? '') == strtolower($productName)
                        && strtolower($transaction['detail']['transaction_status'] ?? '') == strtolower($transactionStatus);
                })
                ->map(function ($transaction, $order_id) {
                    return [
                        'order_id' => $order_id,
                        'product_name' => $transaction['product']['name'] ?? "Unknown",
                        'transaction_status' => strtolower($transaction['detail']['transaction_status']) ?? "unknown",
                        'amount' => $transaction['payment']['amount'] ?? 0,
                        'payment_method' => $transaction['payment']['method'] ?? "",
                        'transaction_time' => $transaction['time']['timestamp'] ?? null
                    ];
                });

            $perPage = 20;
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $currentItems = $transactions->slice(($currentPage - 1) * $perPage, $perPage)->values();
            $paginatedTransactions = new LengthAwarePaginator($currentItems, $transactions->count(), $perPage);
            $paginatedTransactions->setPath($request->url());    

            return view('transaction.detail', compact('paginatedTransactions', 'productName', 'transactionStatus'));
        }

        return back()->with('error', 'Failed to fetch transaction details.');
    }

    public function transactionDetailDevice(Request $request)
    {
        $deviceId = $request->query('device_id');
        $transactionStatus = $request->query('transaction_status');

        $apiUrl = "https://login-bir3msoyja-et.a.run.app";
        $payload = [
            'username' => 'user',
            'password' => 'password'
        ];
        $response = Http::post($apiUrl, $payload);

        if ($response->successful()) {
            $json = $response->json();

            // Ambil dan filter data transaksi sesuai dengan produk dan status yang dipilih
            $transactions = collect($json['data'])
                ->filter(function ($transaction) use ($deviceId, $transactionStatus) {
                    return strtolower($transaction['product']['device_id'] ?? '') == strtolower($deviceId)
                        && strtolower($transaction['detail']['transaction_status'] ?? '') == strtolower($transactionStatus);
                })
                ->map(function ($transaction, $order_id) {
                    return [
                        'order_id' => $order_id,
                        'product_name' => $transaction['product']['name'] ?? "Unknown",
                        'transaction_status' => strtolower($transaction['detail']['transaction_status']) ?? "unknown",
                        'amount' => $transaction['payment']['amount'] ?? 0,
                        'payment_method' => $transaction['payment']['method'] ?? "",
                        'transaction_time' => $transaction['time']['timestamp'] ?? null
                    ];
                });

            $perPage = 20;
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $currentItems = $transactions->slice(($currentPage - 1) * $perPage, $perPage)->values();
            $paginatedTransactions = new LengthAwarePaginator($currentItems, $transactions->count(), $perPage);
            $paginatedTransactions->setPath($request->url());    

            return view('transaction.detaildevice', compact('paginatedTransactions', 'deviceId', 'transactionStatus'));
        }

        return back()->with('error', 'Failed to fetch transaction details.');
    }

    public function transactionDetailPayment(Request $request)
    {
        $paymentMethod = $request->query('payment_method');
        $transactionStatus = $request->query('transaction_status');

        $apiUrl = "https://login-bir3msoyja-et.a.run.app";
        $payload = [
            'username' => 'user',
            'password' => 'password'
        ];
        $response = Http::post($apiUrl, $payload);

        if ($response->successful()) {
            $json = $response->json();

            // Ambil dan filter data transaksi sesuai dengan produk dan status yang dipilih
            $transactions = collect($json['data'])
                ->filter(function ($transaction) use ($paymentMethod, $transactionStatus) {
                    return strtolower($transaction['payment']['method'] ?? '') == strtolower($paymentMethod)
                        && strtolower($transaction['detail']['transaction_status'] ?? '') == strtolower($transactionStatus);
                })
                ->map(function ($transaction, $order_id) {
                    return [
                        'order_id' => $order_id,
                        'product_name' => $transaction['product']['name'] ?? "Unknown",
                        'transaction_status' => strtolower($transaction['detail']['transaction_status']) ?? "unknown",
                        'amount' => $transaction['payment']['amount'] ?? 0,
                        'payment_method' => $transaction['payment']['method'] ?? "",
                        'transaction_time' => $transaction['time']['timestamp'] ?? null
                    ];
                });

                $perPage = 20;
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $currentItems = $transactions->slice(($currentPage - 1) * $perPage, $perPage)->values();
            $paginatedTransactions = new LengthAwarePaginator($currentItems, $transactions->count(), $perPage);
            $paginatedTransactions->setPath($request->url());   

            return view('transaction.detailpayment', compact('paginatedTransactions', 'paymentMethod', 'transactionStatus'));
        }

        return back()->with('error', 'Failed to fetch transaction details.');
    }

    public function byDevice(Request $request)
    {
        
        $apiUrl = "https://login-bir3msoyja-et.a.run.app"; 
        $payload = [
            'username' => 'user',
            'password' => 'password'
        ];
        $response = Http::post($apiUrl, $payload);
        
        if ($response->successful()) {
            $json = $response->json();
    
            // Ambil dan filter data transaksi
            $transactions = collect($json['data'])
                // ->filter(function ($transaction) {
                //     return in_array(strtolower($transaction['detail']['transaction_status']), 
                //         ['settlement', 'cancel', 'refund']);
                // })
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
                            'device_id' => $transaction['product']['device_id'] ?? "",
                            'amount' => $transaction['payment']['amount'] ?? 0
                        ];
                });

            // Grouping & Sum berdasarkan product_name & penjualan perstatus
            $salesSummary = $transactions
                ->groupBy(function ($item) {
                    return $item['device_id'] . '|' . $item['transaction_status'];
                })
                ->map(function ($group) {
                    return [
                        'device_id' => $group->first()['device_id'],
                        'transaction_status' => $group->first()['transaction_status'],
                        'total_sales' => $group->sum('amount')
                    ];
                })
                ->values();

                $perPage = 20;
                $currentPage = LengthAwarePaginator::resolveCurrentPage();
                $currentItems = $salesSummary->slice(($currentPage - 1) * $perPage, $perPage)->values();
                $paginatedTransactions = new LengthAwarePaginator($currentItems, $salesSummary->count(), $perPage);
                $paginatedTransactions->setPath($request->url());

            return view('transaction.by_device', compact('paginatedTransactions'));

        }
    
        return response()->json([
            'success' => false,
            'message' => 'Gagal mengambil data transaksi'
        ], $response->status());

    }

    public function byPaymentMethod(Request $request)
    {
        
        $apiUrl = "https://login-bir3msoyja-et.a.run.app";

        $payload = [
            'username' => 'user',
            'password' => 'password'
        ];

        $response = Http::post($apiUrl, $payload);
        
        if ($response->successful()) {
            $json = $response->json();
    
            $transactions = collect($json['data'])
                // ->filter(function ($transaction) {
                //     return $transaction['detail']['transaction_status'] !== 'cancel';
                // })
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

            // Grouping & Sum berdasarkan product_name & penjualan perstatus
            $salesSummary = $transactions
            ->groupBy(function ($item) {
                return $item['payment_method'] . '|' . $item['transaction_status'];
            })
            ->map(function ($group) {
                return [
                    'payment_method' => $group->first()['payment_method'],
                    'transaction_status' => $group->first()['transaction_status'],
                    'total_sales' => $group->sum('amount')
                ];
            })
            ->values();

            // Grouping & Sum berdasarkan payment_method
            // $salesSummary = $transactions
            //     ->groupBy('payment_method') 
            //     ->map(function ($group) {
            //         return [
            //             'payment_method' => $group->first()['payment_method'],
            //             'total_sales' => $group->sum('amount') 
            //         ];
            //     })->values();

                $perPage = 20;
                $currentPage = LengthAwarePaginator::resolveCurrentPage();
                $currentItems = $salesSummary->slice(($currentPage - 1) * $perPage, $perPage)->values();
                $paginatedTransactions = new LengthAwarePaginator($currentItems, $salesSummary->count(), $perPage);
                $paginatedTransactions->setPath($request->url());

            return view('transaction.by_payment_method', compact('paginatedTransactions'));

        }
    
        return response()->json([
            'success' => false,
            'message' => 'Gagal mengambil data transaksi'
        ], $response->status());
    }
    
}
