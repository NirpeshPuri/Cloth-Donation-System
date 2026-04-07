<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cloth;
use App\Models\ClothRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RequestManageController extends Controller
{
    /**
     * Display a listing of all requests for this admin's collection center
     */
    public function index()
    {
        $adminId = Auth::guard('admin')->id();

        $requests = ClothRequest::with(['receiver', 'cloth'])
            ->where('admin_id', $adminId)
            ->orderBy('created_at', 'desc')
            ->get();

        $pendingCount = $requests->where('status', 'pending')->count();
        $approvedCount = $requests->where('status', 'approved')->count();
        $rejectedCount = $requests->where('status', 'rejected')->count();
        $completedCount = $requests->where('status', 'completed')->count();

        return view('admin.requests.index', compact('requests', 'pendingCount', 'approvedCount', 'rejectedCount', 'completedCount'));
    }

    /**
     * Display the specified request details
     */
    public function show($id)
    {
        $adminId = Auth::guard('admin')->id();

        $request = ClothRequest::with(['receiver', 'cloth'])
            ->where('admin_id', $adminId)
            ->findOrFail($id);

        return view('admin.requests.show', compact('request'));
    }

    /**
     * Approve a request
     */
    public function approve($id)
    {
        $adminId = Auth::guard('admin')->id();

        $clothRequest = ClothRequest::where('admin_id', $adminId)
            ->where('status', 'pending')
            ->findOrFail($id);

        DB::beginTransaction();

        try {
            $cloth = Cloth::findOrFail($clothRequest->cloth_id);

            // Check if enough quantity available
            if ($cloth->quantity < $clothRequest->quantity) {
                return back()->with('error', 'Not enough stock available. Only '.$cloth->quantity.' left.');
            }

            // Reduce cloth quantity
            $cloth->quantity -= $clothRequest->quantity;
            $cloth->save();

            // Update request status
            $clothRequest->status = 'approved';
            $clothRequest->save();

            DB::commit();

            return redirect()->route('admin.requests.index')
                ->with('success', '✓ Request approved! Inventory updated.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Error: '.$e->getMessage());
        }
    }

    /**
     * Reject a request
     */
    public function reject($id)
    {
        $adminId = Auth::guard('admin')->id();

        $clothRequest = ClothRequest::where('admin_id', $adminId)
            ->where('status', 'pending')
            ->findOrFail($id);

        $clothRequest->status = 'rejected';
        $clothRequest->save();

        return redirect()->route('admin.requests.index')
            ->with('success', 'Request rejected successfully');
    }

    /**
     * Mark request as completed
     */
    public function complete($id)
    {
        $adminId = Auth::guard('admin')->id();

        $clothRequest = ClothRequest::where('admin_id', $adminId)
            ->where('status', 'approved')
            ->findOrFail($id);

        $clothRequest->status = 'completed';
        $clothRequest->save();

        return redirect()->route('admin.requests.index')
            ->with('success', 'Request marked as completed');
    }

    /**
     * Bulk approve multiple requests
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'request_ids' => 'required|array',
            'request_ids.*' => 'exists:requests,id',
        ]);

        $adminId = Auth::guard('admin')->id();
        $successCount = 0;
        $errorCount = 0;

        DB::beginTransaction();

        try {
            foreach ($request->request_ids as $id) {
                $clothRequest = ClothRequest::where('admin_id', $adminId)
                    ->where('status', 'pending')
                    ->find($id);

                if ($clothRequest) {
                    $cloth = Cloth::findOrFail($clothRequest->cloth_id);

                    if ($cloth->quantity >= $clothRequest->quantity) {
                        $cloth->quantity -= $clothRequest->quantity;
                        $cloth->save();

                        $clothRequest->status = 'approved';
                        $clothRequest->save();
                        $successCount++;
                    } else {
                        $errorCount++;
                    }
                }
            }

            DB::commit();

            $message = "{$successCount} request(s) approved successfully.";
            if ($errorCount > 0) {
                $message .= " {$errorCount} request(s) failed due to insufficient stock.";
            }

            return redirect()->route('admin.requests.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Error: '.$e->getMessage());
        }
    }

    /**
     * Filter requests by status
     */
    public function filter($status)
    {
        $adminId = Auth::guard('admin')->id();

        $validStatuses = ['pending', 'approved', 'rejected', 'completed'];

        if (! in_array($status, $validStatuses)) {
            return redirect()->route('admin.requests.index');
        }

        $requests = ClothRequest::with(['receiver', 'cloth'])
            ->where('admin_id', $adminId)
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->get();

        $pendingCount = ClothRequest::where('admin_id', $adminId)->where('status', 'pending')->count();
        $approvedCount = ClothRequest::where('admin_id', $adminId)->where('status', 'approved')->count();
        $rejectedCount = ClothRequest::where('admin_id', $adminId)->where('status', 'rejected')->count();
        $completedCount = ClothRequest::where('admin_id', $adminId)->where('status', 'completed')->count();

        return view('admin.requests.index', compact('requests', 'pendingCount', 'approvedCount', 'rejectedCount', 'completedCount'));
    }

    /**
     * Search requests
     */
    public function search(Request $request)
    {
        $adminId = Auth::guard('admin')->id();
        $searchTerm = $request->get('search');

        $requests = ClothRequest::with(['receiver', 'cloth'])
            ->where('admin_id', $adminId)
            ->where(function ($query) use ($searchTerm) {
                $query->whereHas('receiver', function ($q) use ($searchTerm) {
                    $q->where('name', 'like', "%{$searchTerm}%")
                        ->orWhere('email', 'like', "%{$searchTerm}%");
                })
                    ->orWhereHas('cloth', function ($q) use ($searchTerm) {
                        $q->where('name', 'like', "%{$searchTerm}%");
                    });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $pendingCount = ClothRequest::where('admin_id', $adminId)->where('status', 'pending')->count();
        $approvedCount = ClothRequest::where('admin_id', $adminId)->where('status', 'approved')->count();
        $rejectedCount = ClothRequest::where('admin_id', $adminId)->where('status', 'rejected')->count();
        $completedCount = ClothRequest::where('admin_id', $adminId)->where('status', 'completed')->count();

        return view('admin.requests.index', compact('requests', 'pendingCount', 'approvedCount', 'rejectedCount', 'completedCount'));
    }
}
