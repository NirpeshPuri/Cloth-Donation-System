<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cloth;
use App\Models\Donation;
use Illuminate\Support\Facades\DB;

class DonationManageController extends Controller
{
    public function index()
    {
        $donations = Donation::with(['donor', 'items', 'admin'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.donations.index', compact('donations'));
    }

    public function show($id)
    {
        $donation = Donation::with(['donor', 'items', 'admin'])->findOrFail($id);

        return view('admin.donations.show', compact('donation'));
    }

    public function approve($id)
    {
        $donation = Donation::with('items')->findOrFail($id);

        DB::beginTransaction();

        try {
            foreach ($donation->items as $item) {
                // Debug: Print values to log
                \Log::info('=== APPROVING DONATION ITEM ===');
                \Log::info('cloth_name: '.$item->cloth_name);
                \Log::info('cloth_type: '.$item->cloth_type);
                \Log::info('gender: '.$item->gender);
                \Log::info('size: '.$item->size);
                \Log::info('color: '.$item->color);

                // Create cloth with explicit values
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

                \Log::info('Cloth saved with ID: '.$cloth->id);
            }

            $donation->status = 'approved';
            $donation->save();

            DB::commit();

            return redirect()->route('admin.donations.index')
                ->with('success', '✓ Donation approved!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Approval error: '.$e->getMessage());

            return back()->with('error', 'Error: '.$e->getMessage());
        }
    }

    public function reject($id)
    {
        $donation = Donation::findOrFail($id);
        $donation->status = 'rejected';
        $donation->save();

        return redirect()->route('admin.donations.index')
            ->with('success', 'Donation rejected successfully');
    }
}
