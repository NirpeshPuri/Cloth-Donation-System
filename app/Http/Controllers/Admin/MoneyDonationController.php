<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EsewaKhalti;
use Illuminate\Http\Request;

class MoneyDonationController extends Controller
{
    public function index(Request $request)
    {
        $query = EsewaKhalti::with('user')->latest();

        // 🔍 SEARCH
        if ($request->search) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('transaction_id', 'like', "%$search%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'like', "%$search%")
                            ->orWhere('email', 'like', "%$search%");
                    });
            });
        }

        // 🔽 FILTER (eSewa / Khalti)
        if ($request->method == 'esewa') {
            $query->where('transaction_id', 'like', 'txn_%');
        }

        if ($request->method == 'khalti') {
            $query->where('transaction_id', 'not like', 'txn_%');
        }

        $donations = $query->paginate(15);

        // 💰 TOTAL (CLONE query to avoid pagination effect)
        $totalAmount = (clone $query)->sum('amount');

        // 💰 TOTAL COMPLETED
        $totalCompleted = (clone $query)
            ->where('payment_status', 'completed')
            ->sum('amount');

        // 💛 TOTAL PENDING
        $totalPending = (clone $query)
            ->where('payment_status', 'pending')
            ->sum('amount');

        // 🔴 TOTAL FAILED
        $totalFailed = (clone $query)
            ->where('payment_status', 'failed')
            ->sum('amount');

        // ✅ MUST BE JSON
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.partials.donation_rows', compact('donations'))->render(),
                'hasMore' => $donations->hasMorePages(),
            ]);
        }

        return view('admin.moneydonation.money-donations', [
            'donations' => $donations,
            'totalAmount' => $totalAmount,
            'totalCompleted' => $totalCompleted,
            'totalPending' => $totalPending,
            'totalFailed' => $totalFailed,
        ]);
    }

    public function export(Request $request)
    {
        $query = EsewaKhalti::with(['user'])->latest();

        // 🔍 SEARCH
        if ($request->search) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // 🔽 FILTER (eSewa / Khalti)
        if ($request->method == 'esewa') {
            $query->where('transaction_id', 'like', 'txn_%');
        }

        if ($request->method == 'khalti') {
            $query->where('transaction_id', 'not like', 'txn_%');
        }

        // IMPORTANT: get() exports ALL matching records,
        // not only the paginated 15 records.
        $donations = $query->get();

        $filename = 'financial_donations_'.date('Y-m-d_H-i-s').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $columns = [
            'ID',
            'User Name',
            'User Email',
            'Amount',
            'Transaction ID',
            'Payment Status',
            'Date',
        ];

        $callback = function () use ($donations, $columns) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM for Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, $columns);

            foreach ($donations as $donation) {
                fputcsv($file, [
                    $donation->id,
                    $donation->user->name ?? 'N/A',
                    $donation->user->email ?? 'N/A',
                    $donation->amount ?? 0,
                    $donation->transaction_id ?? 'N/A',
                    ucfirst($donation->payment_status ?? 'N/A'),
                    $donation->created_at
                        ? $donation->created_at->format('Y-m-d H:i:s')
                        : 'N/A',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
