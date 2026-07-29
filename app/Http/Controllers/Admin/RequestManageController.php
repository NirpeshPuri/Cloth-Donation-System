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
    public function index(Request $request)
    {
        $adminId = Auth::guard('admin')->id();
        $search = $request->search;
        $status = $request->status;
        $category = $request->category;
        $gender = $request->gender;
        $size = $request->size;
        $color = $request->color;
        $quality = $request->quality;
        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;

        $pendingCount = ClothRequest::where('admin_id', $adminId)->where('status', 'pending')->count();
        $approvedCount = ClothRequest::where('admin_id', $adminId)->where('status', 'approved')->count();
        $rejectedCount = ClothRequest::where('admin_id', $adminId)->where('status', 'rejected')->count();
        $completedCount = ClothRequest::where('admin_id', $adminId)->where('status', 'completed')->count();

        $requests = ClothRequest::with(['receiver', 'cloth', 'admin'])
            ->where('admin_id', $adminId)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('status', 'like', "%{$search}%")
                        ->orWhereHas('receiver', function ($receiver) use ($search) {
                            $receiver->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%");
                        })
                        ->orWhereHas('cloth', function ($cloth) use ($search) {
                            $cloth->where('name', 'like', "%{$search}%")
                                ->orWhere('category', 'like', "%{$search}%")
                                ->orWhere('gender', 'like', "%{$search}%")
                                ->orWhere('size', 'like', "%{$search}%")
                                ->orWhere('color', 'like', "%{$search}%")
                                ->orWhere('quality', 'like', "%{$search}%");
                        });
                });
            })
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($category, fn ($q) => $q->whereHas('cloth', fn ($c) => $c->where('category', $category)))
            ->when($gender, fn ($q) => $q->whereHas('cloth', fn ($c) => $c->where('gender', $gender)))
            ->when($size, fn ($q) => $q->whereHas('cloth', fn ($c) => $c->where('size', $size)))
            ->when($color, fn ($q) => $q->whereHas('cloth', fn ($c) => $c->where('color', 'like', "%{$color}%")))
            ->when($quality, fn ($q) => $q->whereHas('cloth', fn ($c) => $c->where('quality', $quality)))
            ->when($dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('created_at', '<=', $dateTo))
            ->orderBy('created_at', 'desc')
            ->paginate(5)
            ->withQueryString();

        return view('admin.requests.index', compact(
            'requests',
            'search',
            'status',
            'category',
            'gender',
            'size',
            'color',
            'quality',
            'dateFrom',
            'dateTo',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'completedCount'
        ));
    }

    public function show($id)
    {
        $request = ClothRequest::with(['receiver', 'cloth', 'admin'])
            ->where('admin_id', Auth::guard('admin')->id())
            ->findOrFail($id);

        return view('admin.requests.show', compact('request'));
    }

    public function approve($id)
    {
        $adminId = Auth::guard('admin')->id();
        $clothRequest = ClothRequest::where('admin_id', $adminId)
            ->where('status', 'pending')
            ->findOrFail($id);

        DB::beginTransaction();

        try {
            $cloth = Cloth::where('admin_id', $adminId)->findOrFail($clothRequest->cloth_id);

            if ($cloth->quantity < $clothRequest->quantity) {
                DB::rollBack();

                return back()->with('error', 'Not enough stock available. Only '.$cloth->quantity.' left.');
            }

            $cloth->quantity -= $clothRequest->quantity;
            $cloth->save();

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

    public function reject($id)
    {
        $clothRequest = ClothRequest::where('admin_id', Auth::guard('admin')->id())
            ->where('status', 'pending')
            ->findOrFail($id);

        $clothRequest->status = 'rejected';
        $clothRequest->save();

        return redirect()->route('admin.requests.index')
            ->with('success', 'Request rejected successfully');
    }

    public function complete($id)
    {
        $clothRequest = ClothRequest::where('admin_id', Auth::guard('admin')->id())
            ->where('status', 'approved')
            ->findOrFail($id);

        $clothRequest->status = 'completed';
        $clothRequest->save();

        return redirect()->route('admin.requests.index')
            ->with('success', 'Request marked as completed');
    }

    public function export(Request $request)
    {
        $adminId = Auth::guard('admin')->id();
        $search = $request->search;
        $status = $request->status;
        $category = $request->category;
        $gender = $request->gender;
        $size = $request->size;
        $color = $request->color;
        $quality = $request->quality;
        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;

        $requests = ClothRequest::with(['receiver', 'cloth', 'admin'])
            ->where('admin_id', $adminId)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('status', 'like', "%{$search}%")
                        ->orWhereHas('receiver', function ($receiver) use ($search) {
                            $receiver->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%");
                        })
                        ->orWhereHas('cloth', function ($cloth) use ($search) {
                            $cloth->where('name', 'like', "%{$search}%")
                                ->orWhere('category', 'like', "%{$search}%")
                                ->orWhere('gender', 'like', "%{$search}%")
                                ->orWhere('size', 'like', "%{$search}%")
                                ->orWhere('color', 'like', "%{$search}%")
                                ->orWhere('quality', 'like', "%{$search}%");
                        });
                });
            })
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($category, fn ($q) => $q->whereHas('cloth', fn ($c) => $c->where('category', $category)))
            ->when($gender, fn ($q) => $q->whereHas('cloth', fn ($c) => $c->where('gender', $gender)))
            ->when($size, fn ($q) => $q->whereHas('cloth', fn ($c) => $c->where('size', $size)))
            ->when($color, fn ($q) => $q->whereHas('cloth', fn ($c) => $c->where('color', 'like', "%{$color}%")))
            ->when($quality, fn ($q) => $q->whereHas('cloth', fn ($c) => $c->where('quality', $quality)))
            ->when($dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('created_at', '<=', $dateTo))
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'requests_'.date('Y-m-d_H-i-s').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $columns = [
            'Request ID',
            'Receiver Name',
            'Receiver Email',
            'Receiver Phone',
            'Cloth Name',
            'Category',
            'Gender',
            'Size',
            'Color',
            'Quantity',
            'Quality',
            'Description',
            'Request Status',
            'Admin Name',
            'Request Date',
        ];

        $callback = function () use ($requests, $columns) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $columns);

            foreach ($requests as $request) {
                fputcsv($file, [
                    $request->id,
                    $request->receiver->name ?? 'N/A',
                    $request->receiver->email ?? 'N/A',
                    $request->receiver->phone ?? 'N/A',
                    $request->cloth->name ?? 'N/A',
                    $request->cloth->category ?? 'N/A',
                    $request->cloth->gender ?? 'N/A',
                    $request->cloth->size ?? 'N/A',
                    $request->cloth->color ?? 'N/A',
                    $request->quantity ?? 0,
                    $request->cloth->quality ?? 'N/A',
                    $request->cloth->description ?? 'N/A',
                    ucfirst($request->status),
                    $request->admin->name ?? 'N/A',
                    $request->created_at ? $request->created_at->format('Y-m-d') : 'N/A',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
