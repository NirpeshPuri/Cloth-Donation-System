<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function edit($id)
    {
        $admin = DB::table('admins')->where('id', $id)->first();

        return view('admin.edit', compact('admin'));
    }

    public function updatePhoto(Request $request, $id)
    {
        // Validate
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Check if file exists
        if (! $request->hasFile('profile_photo')) {
            return back()->with('error', 'No file selected!');
        }

        $file = $request->file('profile_photo');

        // Check if upload is valid
        if (! $file->isValid()) {
            return back()->with('error', 'File upload error!');
        }

        // Generate filename
        $filename = time().'_'.$file->getClientOriginalName();

        // Destination path
        $destination = public_path('images/admin_photo');

        // Create folder if not exists
        if (! file_exists($destination)) {
            mkdir($destination, 0777, true);
        }

        // Move file (IMPORTANT PART)
        try {
            $file->move($destination, $filename);
        } catch (\Exception $e) {
            return back()->with('error', 'Upload failed: '.$e->getMessage());
        }

        // Save to DB
        DB::table('admins')->where('id', $id)->update([
            'profile_photo' => 'images/admin_photo/'.$filename,
        ]);

        return back()->with('success', 'Photo uploaded successfully!');
    }
}
