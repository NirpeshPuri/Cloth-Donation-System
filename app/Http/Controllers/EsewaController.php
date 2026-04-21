<?php

namespace App\Http\Controllers;

use App\Models\EsewaKhalti;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EsewaController extends Controller
{
    // STEP 1: Store donation in session
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10',
        ]);

        $request->session()->put('donation', [
            'user_id' => auth()->id(),
            'amount' => $request->amount,
        ]);

        return response()->json([
            'redirect_url' => route('esewa.pay'),
        ]);
    }

    // STEP 2: Redirect to eSewa
    public function pay(Request $request)
    {
        $donation = $request->session()->get('donation');

        if (! $donation) {
            return redirect()->route('user.donate-money')
                ->with('error', 'Session expired');
        }

        // ✅ DEFINE THIS FIRST
        $amount = $donation['amount'];
        $tax = 0;
        $total = $amount + $tax;

        $transaction_uuid = uniqid('txn_').time();
        $secret_key = '8gBm/:&EnhH.1/q'; // your secret key
        $product_code = 'EPAYTEST';

        $signature_string = "total_amount={$donation['amount']},transaction_uuid={$transaction_uuid},product_code={$product_code}";
        $signature = base64_encode(hash_hmac('sha256', $signature_string, $secret_key, true));

        return view('user.esewa-payment', [
            'amount' => $donation['amount'],
            'total_amount' => $donation['amount'],
            'tax' => $tax,
            'total' => $total,
            'transaction_uuid' => $transaction_uuid,
            'product_code' => $product_code,
            'success_url' => route('esewa.success'),
            'failure_url' => route('esewa.failure'),
            'signature' => $signature,
            'signed_field_names' => 'total_amount,transaction_uuid,product_code',
        ]);
    }

    // STEP 3: Success
    public function success(Request $request)
    {
        try {
            $jsonData = $request->input('data');
            $paymentData = json_decode(base64_decode($jsonData), true);

            if (! $paymentData) {
                throw new \Exception('Invalid payment data');
            }

            $transactionId = $paymentData['transaction_uuid'] ?? null;
            $amount = $paymentData['total_amount'] ?? null;
            $status = $paymentData['status'] ?? null;

            $session = $request->session()->get('donation');

            if (! $session) {
                throw new \Exception('Session expired');
            }

            if ($status !== 'COMPLETE') {
                throw new \Exception('Payment not completed');
            }

            // ✅ STORE DONATION
            EsewaKhalti::create([
                'user_id' => $session['user_id'],
                'amount' => str_replace(',', '', $amount),
                'transaction_id' => $transactionId,
                'payment_status' => 'completed',
            ]);

            $request->session()->forget('donation');

            return view('user.payment_success', [
                'transaction_id' => $transactionId,
                'amount' => $amount,
            ]);

        } catch (\Exception $e) {
            return redirect()->route('esewa.failure')
                ->with('error', $e->getMessage());
        }
    }

    // STEP 4: Failure
    public function failure()
    {
        return view('user.payment_failure');
    }

    private function verifyEsewaPayment($refId, $oid, $amount)
    {
        if (app()->environment('local', 'testing')) {
            return true;
        }

        try {
            $verificationUrl = 'https://rc-epay.esewa.com.np/api/epay/transaction/status/'.$oid;

            $client = new Client;
            $response = $client->request('POST', $verificationUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'transaction_uuid' => $oid,
                    'total_amount' => $amount,
                ],
            ]);

            $responseData = json_decode($response->getBody(), true);

            return isset($responseData['status']) &&
                $responseData['status'] === 'COMPLETE' &&
                $responseData['total_amount'] == $amount &&
                $responseData['transaction_uuid'] == $oid;

        } catch (\Exception $e) {
            return false;
        }
    }

    // --------< This part is of Khalti >----------
    // public function initiate(Request $request)
    // {
    //     try {
    //         $amount = $request->input('amount');

    //         $response = Http::withOptions([
    //             'verify' => false,
    //             'curl' => [
    //                 CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
    //             ],
    //         ])->timeout(30) // increase timeout
    //             ->withHeaders([
    //                 'Authorization' => 'Key '.config('services.khalti.secret'),
    //                 'Content-Type' => 'application/json',
    //             ])->post('https://a.khalti.com/api/v2/epayment/initiate/', [
    //                 'return_url' => route('khalti.verify'),
    //                 'website_url' => url('/'),
    //                 'amount' => $request->amount * 100,
    //                 'purchase_order_id' => uniqid(),
    //                 'purchase_order_name' => 'Donation',
    //             ]);

    //         $data = $response->json();

    //         if (! $response->successful()) {
    //             return response()->json([
    //                 'error' => 'Khalti failed',
    //                 'details' => $data,
    //             ]);
    //         }

    //         // ✅ SAVE WITH CORRECT pidx
    //         EsewaKhalti::create([
    //             'user_id' => auth()->id(),
    //             'amount' => $amount,
    //             'transaction_id' => $data['pidx'], // ✅ correct
    //             'payment_status' => 'pending',
    //         ]);

    //         return response()->json([
    //             'payment_url' => $data['payment_url'],
    //         ]);

    //     } catch (\Throwable $e) {
    //         return response()->json([
    //             'error' => $e->getMessage(),
    //         ]);
    //     }
    // }

    // public function verify(Request $request)
    // {
    //     // $session = session('khalti');

    //     // if (! $session) {
    //     //     return redirect()->route('esewa.failure')
    //     //         ->with('error', 'Session expired');
    //     // }

    //     $pidx = $request->pidx;

    //     $payment = EsewaKhalti::where('transaction_id', $pidx)->first();

    //     if (! $payment) {
    //         return redirect()->route('esewa.failure')
    //             ->with('error', 'Payment record not found');
    //     }

    //     $response = Http::withOptions([
    //         'verify' => false,
    //         'curl' => [
    //             CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
    //         ],
    //     ])->timeout(60)
    //         ->withHeaders([
    //             'Authorization' => 'Key '.config('services.khalti.secret'),
    //             'Content-Type' => 'application/json',
    //         ])->post('https://a.khalti.com/api/v2/epayment/lookup/', [
    //             'pidx' => $request->pidx,
    //         ]);

    //     $data = $response->json();

    //     if (($data['status'] ?? null) === 'Completed') {

    //         $amount = $data['total_amount'] / 100; // convert paisa → rupees

    //         $payment->update([
    //             'payment_status' => 'completed',
    //             'amount' => $amount,
    //         ]);

    //         return redirect()->route('khalti.success', [
    //             'pidx' => $pidx]);
    //     }

    //     $payment->update([
    //         'payment_status' => 'failed',
    //     ]);

    //     return redirect()->route('esewa.failure')
    //         ->with('error', 'Payment failed');
    // }

    // public function khaltiSuccess(Request $request)
    // {
    //     $pidx = $request->pidx;

    //     $payment = EsewaKhalti::where('transaction_id', $pidx)->first();

    //     if (! $payment) {
    //         return redirect()->route('esewa.failure')
    //             ->with('error', 'Payment record not found');
    //     }

    //     return view('user.payment_success', [
    //         'transaction_id' => $payment->transaction_id,
    //         'amount' => $payment->amount,
    //     ]);
    // }
}
