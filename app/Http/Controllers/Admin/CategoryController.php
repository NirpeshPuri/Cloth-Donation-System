<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Setting::getCategories();

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'value' => 'required|string|max:50',
            'label' => 'required|string|max:100',
            'icon' => 'nullable|string',
        ]);

        $categories = Setting::getCategories();

        // Check if category already exists
        foreach ($categories as $cat) {
            if ($cat['value'] === $request->value) {
                return back()->with('error', 'Category already exists!');
            }
        }

        $categories[] = [
            'value' => $request->value,
            'label' => $request->label,
            'icon' => $request->icon ?? 'fa-tag',
        ];

        Setting::updateCategories($categories);

        return back()->with('success', 'Category added successfully!');
    }

    public function update(Request $request, $index)
    {
        $request->validate([
            'value' => 'required|string|max:50',
            'label' => 'required|string|max:100',
            'icon' => 'nullable|string',
        ]);

        $categories = Setting::getCategories();

        if (isset($categories[$index])) {
            $categories[$index] = [
                'value' => $request->value,
                'label' => $request->label,
                'icon' => $request->icon ?? $categories[$index]['icon'] ?? 'fa-tag',
            ];
            Setting::updateCategories($categories);

            return back()->with('success', 'Category updated successfully!');
        }

        return back()->with('error', 'Category not found!');
    }

    public function destroy($index)
    {
        $categories = Setting::getCategories();

        if (isset($categories[$index])) {
            array_splice($categories, $index, 1);
            Setting::updateCategories($categories);

            return back()->with('success', 'Category deleted successfully!');
        }

        return back()->with('error', 'Category not found!');
    }
}
