<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cloth;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DonationManageController extends Controller
{
    public function index(Request $request)
    {
        $adminId = auth('admin')->id();
        $search = $request->search;
        $clothType = $request->cloth_type;
        $gender = $request->gender;
        $size = $request->size;
        $color = $request->color;
        $status = $request->status;
        $quality = $request->quality;
        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;
        $pendingCount = Donation::where('admin_id', $adminId)->where('status', 'pending')->count();
        $approvedCount = Donation::where('admin_id', $adminId)->where('status', 'approved')->count();
        $rejectedCount = Donation::where('admin_id', $adminId)->where('status', 'rejected')->count();
        $completedCount = Donation::where('admin_id', $adminId)->where('status', 'completed')->count();

        $donations = Donation::with(['donor', 'items', 'admin'])
            ->where('admin_id', $adminId)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('status', 'like', "%{$search}%")
                        ->orWhere('donation_type', 'like', "%{$search}%")
                        ->orWhereHas('donor', function ($donor) use ($search) {
                            $donor->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%");
                        })
                        ->orWhereHas('items', function ($item) use ($search) {
                            $item->where('cloth_name', 'like', "%{$search}%")
                                ->orWhere('cloth_type', 'like', "%{$search}%")
                                ->orWhere('gender', 'like', "%{$search}%")
                                ->orWhere('size', 'like', "%{$search}%")
                                ->orWhere('color', 'like', "%{$search}%")
                                ->orWhere('quality', 'like', "%{$search}%");
                        });
                });
            })
            ->when($clothType, function ($query) use ($clothType) {
                $query->whereHas('items', function ($q) use ($clothType) {
                    $q->where('cloth_type', $clothType);
                });
            })
            ->when($gender, function ($query) use ($gender) {
                $query->whereHas('items', function ($q) use ($gender) {
                    $q->where('gender', $gender);
                });
            })
            ->when($size, function ($query) use ($size) {
                $query->whereHas('items', function ($q) use ($size) {
                    $q->where('size', $size);
                });
            })
            ->when($color, function ($query) use ($color) {
                $query->whereHas('items', function ($q) use ($color) {
                    $q->where('color', $color);
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($quality, function ($query) use ($quality) {
                $query->whereHas('items', function ($q) use ($quality) {
                    $q->where('quality', $quality);
                });
            })
            ->when($dateFrom, function ($query) use ($dateFrom) {
                $query->whereDate('date_of_donation', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query) use ($dateTo) {
                $query->whereDate('date_of_donation', '<=', $dateTo);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(5)
            ->withQueryString();

        return view('admin.donations.index', compact(
            'donations',
            'search',
            'clothType',
            'gender',
            'size',
            'color',
            'status',
            'quality',
            'dateFrom',
            'dateTo',
            'pendingCount', 'approvedCount', 'rejectedCount', 'completedCount'
        ));
    }

    public function show($id)
    {
        $donation = Donation::with(['donor', 'items', 'admin'])
            ->where('admin_id', auth('admin')->id())
            ->findOrFail($id);

        return view('admin.donations.show', compact('donation'));
    }

    public function approve($id)
    {
        $donation = Donation::with('items')
            ->where('admin_id', auth('admin')->id())
            ->findOrFail($id);

        DB::beginTransaction();

        try {
            foreach ($donation->items as $item) {
                $cloth = new Cloth;
                $cloth->name = $item->cloth_name;
                $cloth->category = $item->cloth_type;
                $cloth->gender = $item->gender;
                $cloth->size = $item->size;
                $cloth->color = $item->color;
                $cloth->quantity = $item->quantity;
                $cloth->quality = $item->quality;
                $cloth->description = $item->description;
                $cloth->image_path = $item->image_path;
                $cloth->admin_id = $donation->admin_id;
                $cloth->donor_id = $donation->donor_id;
                $cloth->status = 'available';
                $cloth->save();
            }

            $donation->status = 'approved';
            $donation->save();

            DB::commit();

            return redirect()->route('admin.donations.index')
                ->with('success', '✓ Donation approved!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Error: '.$e->getMessage());
        }
    }

    public function reject($id)
    {
        $donation = Donation::where('admin_id', auth('admin')->id())
            ->findOrFail($id);

        $donation->status = 'rejected';
        $donation->save();

        return redirect()->route('admin.donations.index')
            ->with('success', 'Donation rejected successfully');
    }

    public function export(Request $request)
    {
        $adminId = auth('admin')->id();
        $search = $request->search;
        $clothType = $request->cloth_type;
        $gender = $request->gender;
        $size = $request->size;
        $color = $request->color;
        $quantity = $request->quantity;
        $quality = $request->quality;
        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;

        $donations = Donation::with(['donor', 'items', 'admin'])
            ->where('admin_id', $adminId)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('status', 'like', "%{$search}%")
                        ->orWhere('donation_type', 'like', "%{$search}%")
                        ->orWhereHas('donor', function ($donor) use ($search) {
                            $donor->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%");
                        })
                        ->orWhereHas('items', function ($item) use ($search) {
                            $item->where('cloth_name', 'like', "%{$search}%")
                                ->orWhere('cloth_type', 'like', "%{$search}%")
                                ->orWhere('gender', 'like', "%{$search}%")
                                ->orWhere('size', 'like', "%{$search}%")
                                ->orWhere('color', 'like', "%{$search}%")
                                ->orWhere('quality', 'like', "%{$search}%");
                        });
                });
            })
            ->when($clothType, fn ($q) => $q->whereHas('items', fn ($i) => $i->where('cloth_type', $clothType)))
            ->when($gender, fn ($q) => $q->whereHas('items', fn ($i) => $i->where('gender', $gender)))
            ->when($size, fn ($q) => $q->whereHas('items', fn ($i) => $i->where('size', $size)))
            ->when($color, fn ($q) => $q->whereHas('items', fn ($i) => $i->where('color', $color)))
            ->when($quantity, fn ($q) => $q->whereHas('items', fn ($i) => $i->where('quantity', $quantity)))
            ->when($quality, fn ($q) => $q->whereHas('items', fn ($i) => $i->where('quality', $quality)))
            ->when($dateFrom, fn ($q) => $q->whereDate('date_of_donation', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('date_of_donation', '<=', $dateTo))
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'donations_'.date('Y-m-d_H-i-s').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $columns = [
            'Donation ID',
            'Donor Name',
            'Donor Email',
            'Donor Phone',
            'Donation Type',
            'Pickup Address',
            'Notes',
            'Item Name',
            'Cloth Type',
            'Gender',
            'Size',
            'Color',
            'Quantity',
            'Quality',
            'Description',
            'Status',
            'Admin Name',
            'Donation Date',
        ];

        $callback = function () use ($donations, $columns) {
            $file = fopen('php://output', 'w');

            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $columns);

            foreach ($donations as $donation) {
                foreach ($donation->items as $item) {
                    fputcsv($file, [
                        $donation->id,
                        $donation->donor->name ?? 'N/A',
                        $donation->donor->email ?? 'N/A',
                        $donation->donor->phone ?? 'N/A',
                        $donation->donation_type ?? 'N/A',
                        $donation->pickup_address ?? 'N/A',
                        $donation->notes ?? 'N/A',
                        $item->cloth_name ?? 'N/A',
                        $item->cloth_type ?? 'N/A',
                        $item->gender ?? 'N/A',
                        $item->size ?? 'N/A',
                        $item->color ?? 'N/A',
                        $item->quantity ?? 0,
                        $item->quality ?? 'N/A',
                        $item->description ?? 'N/A',
                        ucfirst($donation->status),
                        $donation->admin->name ?? 'N/A',
                        $donation->date_of_donation
                            ? date('Y-m-d', strtotime($donation->date_of_donation))
                            : 'N/A',
                    ]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
