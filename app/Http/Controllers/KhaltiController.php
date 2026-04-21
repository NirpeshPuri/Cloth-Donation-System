<?php

namespace App\Http\Controllers;

use App\Models\EsewaKhalti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class KhaltiController extends Controller
{
    public function initiate(Request $request)
    {
        try {
            $amount = $request->input('amount');

            $response = Http::withOptions([
                'verify' => false,
                'curl' => [
                    CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                ],
            ])->timeout(30) // increase timeout
                ->withHeaders([
                    'Authorization' => 'Key '.config('services.khalti.secret'),
                    'Content-Type' => 'application/json',
                ])->post('https://a.khalti.com/api/v2/epayment/initiate/', [
                    'return_url' => route('khalti.verify'),
                    'website_url' => url('/'),
                    'amount' => $request->amount * 100,
                    'purchase_order_id' => uniqid(),
                    'purchase_order_name' => 'Donation',
                ]);

            $data = $response->json();

            if (! $response->successful()) {
                return response()->json([
                    'error' => 'Khalti failed',
                    'details' => $data,
                ]);
            }

            // ✅ SAVE WITH CORRECT pidx
            EsewaKhalti::create([
                'user_id' => auth()->id(),
                'amount' => $amount,
                'transaction_id' => $data['pidx'],
                'payment_status' => 'pending',
            ]);

            return response()->json([
                'payment_url' => $data['payment_url'],
            ]);

        } catch (Throwable $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function verify(Request $request)
    {
        $pidx = $request->pidx;

        if (! $pidx) {
            return redirect()->route('khalti.failure');
        }

        $payment = EsewaKhalti::where('transaction_id', $pidx)->first();

        if (! $payment) {
            return redirect()->route('khalti.failure')
                ->with('error', 'Payment record not found');
        }

        // 🔥 CALL KHALTI VERIFY API
        $response = Http::withOptions([
            'verify' => false,
            'curl' => [
                CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
            ],
        ])->timeout(60)
            ->withHeaders([
                'Authorization' => 'Key '.config('services.khalti.secret'),
                'Content-Type' => 'application/json',
            ])->post('https://a.khalti.com/api/v2/epayment/lookup/', [
                'pidx' => $pidx,
            ]);

        $data = $response->json();

        // ✅ SUCCESS CASE
        if (($data['status'] ?? null) === 'Completed') {

            $amount = $data['total_amount'] / 100;

            $payment->update([
                'payment_status' => 'completed',
                'amount' => $amount,
            ]);

            return redirect()->route('khalti.success', [
                'pidx' => $pidx,
            ]);
        }

        // ❌ FAILURE CASE
        $payment->update([
            'payment_status' => 'failed',
        ]);

        return redirect()->route('khalti.failure')
            ->with('error', 'Payment failed');
    }

    public function khaltiSuccess(Request $request)
    {
        $pidx = $request->pidx;

        $payment = EsewaKhalti::where('transaction_id', $pidx)->first();

        if (! $payment) {
            return redirect()->route('khalti.failure')
                ->with('error', 'Payment record not found');
        }

        return view('user.payment_success', [
            'transaction_id' => $payment->transaction_id,
            'amount' => $payment->amount,
        ]);
    }

    public function khaltiFailure(Request $request)
    {
        $pidx = $request->pidx;

        if ($pidx) {
            $payment = EsewaKhalti::where('transaction_id', $pidx)->first();

            if ($payment) {
                $payment->update([
                    'payment_status' => 'failed',
                ]);
            }
        }

        return view('user.payment_failure', [
            'message' => 'Your Khalti payment was not completed.',
        ]);
    }
}
