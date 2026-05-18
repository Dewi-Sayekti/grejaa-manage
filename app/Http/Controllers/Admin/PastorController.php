<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pastor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PastorController extends Controller
{
    public function index()
    {
        $pastors = Pastor::all();
        return view('admin.pastors.index', compact('pastors'));
    }

    public function create()
    {
        return view('admin.pastors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
        ]);

        $data = $request->only(['name', 'role', 'description']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('pastors', 'public');
            $data['image_path'] = 'storage/' . $path;
        }

        Pastor::create($data);

        return redirect()->route('admin.pastors.index')->with('success', 'Data Pemimpin berhasil ditambahkan.');
    }

    public function edit(Pastor $pastor)
    {
        return view('admin.pastors.edit', compact('pastor'));
    }

    public function update(Request $request, Pastor $pastor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
        ]);

        $data = $request->only(['name', 'role', 'description']);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($pastor->image_path && str_starts_with($pastor->image_path, 'storage/')) {
                Storage::disk('public')->delete(str_replace('storage/', '', $pastor->image_path));
            }

            $path = $request->file('image')->store('pastors', 'public');
            $data['image_path'] = 'storage/' . $path;
        }

        $pastor->update($data);

        return redirect()->route('admin.pastors.index')->with('success', 'Data Pemimpin berhasil diperbarui.');
    }

    public function destroy(Pastor $pastor)
    {
        if ($pastor->image_path && str_starts_with($pastor->image_path, 'storage/')) {
            Storage::disk('public')->delete(str_replace('storage/', '', $pastor->image_path));
        }
        $pastor->delete();

        return redirect()->route('admin.pastors.index')->with('success', 'Data Pemimpin berhasil dihapus.');
    }
}
