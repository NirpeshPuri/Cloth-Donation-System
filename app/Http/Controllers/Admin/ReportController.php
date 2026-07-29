<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cloth;
use App\Models\ClothRequest;
use App\Models\Donation;
use App\Models\DonationItem;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Reports dashboard
     */
    public function index()
    {
        $adminId = Auth::guard('admin')->id();

        // Overview stats
        $totalDonations = Donation::count();
        $totalClothes = Cloth::where('admin_id', $adminId)->sum('quantity');
        $totalRequests = ClothRequest::where('admin_id', $adminId)->count();
        $totalUsers = User::count();

        // Monthly trends (last 6 months)
        $monthlyDonations = Donation::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $monthlyRequests = ClothRequest::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('COUNT(*) as count')
        )
            ->where('admin_id', $adminId)
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Status distribution
        $donationStatus = Donation::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        $requestStatus = ClothRequest::where('admin_id', $adminId)
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        // Gender distribution of users
        $genderDistribution = User::select('gender', DB::raw('COUNT(*) as count'))
            ->whereNotNull('gender')
            ->groupBy('gender')
            ->get();

        // Top donated categories
        $topCategories = DonationItem::select('cloth_type', DB::raw('SUM(quantity) as total'))
            ->whereNotNull('cloth_type')
            ->groupBy('cloth_type')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        // Category breakdown in inventory
        $categoryBreakdown = Cloth::where('admin_id', $adminId)
            ->select('category', DB::raw('SUM(quantity) as total'))
            ->whereNotNull('category')
            ->groupBy('category')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        // Gender breakdown of clothes in inventory
        $clothGenderBreakdown = Cloth::where('admin_id', $adminId)
            ->select('gender', DB::raw('SUM(quantity) as total'))
            ->whereNotNull('gender')
            ->groupBy('gender')
            ->orderBy('total', 'desc')
            ->get();

        // Recent activity (last 10 actions)
        $recentDonations = Donation::with(['donor', 'items'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentRequests = ClothRequest::with(['receiver', 'cloth'])
            ->where('admin_id', $adminId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.reports.index', compact(
            'totalDonations',
            'totalClothes',
            'totalRequests',
            'totalUsers',
            'monthlyDonations',
            'monthlyRequests',
            'donationStatus',
            'requestStatus',
            'genderDistribution',
            'topCategories',
            'categoryBreakdown',
            'clothGenderBreakdown',
            'recentDonations',
            'recentRequests'
        ));
    }
}
