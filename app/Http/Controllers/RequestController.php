<?php

namespace App\Http\Controllers;

use App\Models\Cloth;
use App\Models\ClothRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'cloth_id' => 'required|exists:clothes,id',
                'quantity' => 'nullable|integer|min:1',
            ]);

            $cloth = Cloth::findOrFail($request->cloth_id);
            $quantity = $request->quantity ?? 1;

            if ($cloth->quantity < $quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not enough stock available',
                ]);
            }

            DB::beginTransaction();

            // Make sure to include ALL required fields
            $clothRequest = ClothRequest::create([
                'receiver_id' => Auth::id(),  // THIS IS REQUIRED - THE USER REQUESTING
                'cloth_id' => $cloth->id,
                'admin_id' => $cloth->admin_id,
                'quantity' => $quantity,
                'status' => 'pending',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Request submitted successfully!',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error: '.$e->getMessage(),
            ]);
        }
    }

    public function myRequests()
    {
        $requests = ClothRequest::with(['cloth', 'admin'])
            ->where('receiver_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.my-requests', compact('requests'));
    }

    public function cancel($id)
    {
        try {
            \Log::info('Cancel request attempt', [
                'request_id' => $id,
                'user_id' => Auth::id(),
            ]);

            $clothRequest = ClothRequest::where('receiver_id', Auth::id())
                ->where('id', $id)
                ->where('status', 'pending')
                ->first();

            \Log::info('Request found', ['request' => $clothRequest]);

            if (! $clothRequest) {
                return redirect()->route('user.my-requests')
                    ->with('error', 'Request not found or cannot be cancelled');
            }

            $clothRequest->status = 'cancelled';
            $clothRequest->save();

            \Log::info('Request cancelled successfully');

            return redirect()->route('user.my-requests')
                ->with('success', 'Request cancelled successfully');

        } catch (\Exception $e) {
            \Log::error('Cancel error: '.$e->getMessage());

            return redirect()->route('user.my-requests')
                ->with('error', 'Error cancelling request: '.$e->getMessage());
        }
    }
}
