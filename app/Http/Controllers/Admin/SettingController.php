<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        // Get current admin
        $admin = Auth::guard('admin')->user();

        // Get all admins (for main admin to manage)
        $admins = Admin::orderBy('created_at', 'desc')->get();

        // Check if current admin is main admin
        $isMainAdmin = $admin->email === 'bouddha.donation@gmail.com';

        // Get database size
        $databaseSize = $this->getDatabaseSize();

        return view('admin.settings.index', compact('admin', 'admins', 'isMainAdmin', 'databaseSize'));
    }

    /**
     * Update admin profile
     */
    public function updateProfile(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,'.$admin->id,
            'phone' => 'nullable|string|max:20',
            'current_password' => 'nullable|required_with:password|string',
            'password' => 'nullable|string|min:8|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Verify current password if changing password
        if ($request->filled('password')) {
            if (! Hash::check($request->current_password, $admin->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
        }

        // Update admin data
        $adminData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? $admin->phone,
        ];

        // Update password if provided
        if ($request->filled('password')) {
            $adminData['password'] = Hash::make($validated['password']);
        }

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            if ($admin->profile_photo) {
                Storage::disk('public')->delete($admin->profile_photo);
            }
            $adminData['profile_photo'] = $request->file('profile_photo')->store('admin_profiles', 'public');
        }

        $admin->update($adminData);

        return redirect()->route('admin.settings.index')->with('success', 'Profile updated successfully!');
    }

    /**
     * Add new admin (only main admin can do this)
     */
    public function addAdmin(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        // Check if current admin is main admin
        if ($admin->email !== 'bouddha.donation@gmail.com') {
            return back()->with('error', 'Only the main admin can add new admins.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $adminData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
        ];

        // Handle profile photo
        if ($request->hasFile('profile_photo')) {
            $adminData['profile_photo'] = $request->file('profile_photo')->store('admin_profiles', 'public');
        }

        Admin::create($adminData);

        return redirect()->route('admin.settings.index')->with('success', 'New admin added successfully!');
    }

    /**
     * Delete admin (only main admin can do this)
     */
    public function deleteAdmin($id)
    {
        $admin = Auth::guard('admin')->user();

        // Check if current admin is main admin
        if ($admin->email !== 'bouddha.donation@gmail.com') {
            return back()->with('error', 'Only the main admin can delete admins.');
        }

        $adminToDelete = Admin::findOrFail($id);

        // Prevent deleting the main admin
        if ($adminToDelete->email === 'bouddha.donation@gmail.com') {
            return back()->with('error', 'Cannot delete the main admin.');
        }

        // Delete profile photo if exists
        if ($adminToDelete->profile_photo) {
            Storage::disk('public')->delete($adminToDelete->profile_photo);
        }

        $adminToDelete->delete();

        return redirect()->route('admin.settings.index')->with('success', 'Admin deleted successfully!');
    }

    /**
     * Export database backup
     */
    public function backupDatabase()
    {
        try {
            $database = config('database.connections.mysql.database');
            $tables = [];
            $result = DB::select('SHOW TABLES');

            foreach ($result as $row) {
                $table = array_values((array) $row)[0];
                $tables[] = $table;
            }

            $return = '';
            foreach ($tables as $table) {
                $return .= $this->backupTable($table);
            }

            $filename = 'backup_'.date('Y-m-d_H-i-s').'.sql';
            $path = storage_path('app/backups/'.$filename);

            if (! file_exists(storage_path('app/backups'))) {
                mkdir(storage_path('app/backups'), 0777, true);
            }

            file_put_contents($path, $return);

            return response()->download($path)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return redirect()->route('admin.settings.index')->with('error', 'Backup failed: '.$e->getMessage());
        }
    }

    /**
     * Helper function to backup a single table
     */
    private function backupTable($table)
    {
        $return = "DROP TABLE IF EXISTS `$table`;\n";

        $createQuery = DB::select("SHOW CREATE TABLE `$table`");
        $createTable = $createQuery[0]->{'Create Table'};
        $return .= $createTable.";\n\n";

        $rows = DB::table($table)->get();
        if ($rows->count() > 0) {
            $columns = array_keys((array) $rows[0]);
            $values = [];

            foreach ($rows as $row) {
                $rowData = [];
                foreach ($columns as $col) {
                    $rowData[] = "'".addslashes((string) $row->$col)."'";
                }
                $values[] = '('.implode(',', $rowData).')';
            }

            $return .= "INSERT INTO `$table` (`".implode('`, `', $columns)."`) VALUES \n";
            $return .= implode(",\n", $values).";\n\n";
        }

        return $return;
    }

    /**
     * Get database size
     */
    private function getDatabaseSize()
    {
        try {
            $database = config('database.connections.mysql.database');
            $result = DB::select('SELECT SUM(data_length + index_length) AS size
                                  FROM information_schema.tables
                                  WHERE table_schema = ?', [$database]);

            if ($result && isset($result[0]->size)) {
                $size = $result[0]->size;
                if ($size < 1024) {
                    return $size.' B';
                } elseif ($size < 1048576) {
                    return round($size / 1024, 2).' KB';
                } elseif ($size < 1073741824) {
                    return round($size / 1048576, 2).' MB';
                } else {
                    return round($size / 1073741824, 2).' GB';
                }
            }

            return 'N/A';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }
}
