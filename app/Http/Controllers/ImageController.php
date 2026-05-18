<?php

namespace App\Http\Controllers;

use App\Models\HeroSlider;
use App\Models\Image;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * Display the landing page with hero sliders
     */
    public function index(): View
    {
        $heroSliders = HeroSlider::where('is_active', true)
            ->orderBy('order')
            ->get()
            ->toArray();

        $images = Image::all();

        return view('welcome', [
            'heroSliders' => $heroSliders,
            'images' => $images,
        ]);
    }

    public function gallery(): View
    {
        $images = Image::latest()->get();
        return view('image.gallery', compact('images'));
    }

    public function show($id)
    {
        $image = Image::findOrFail($id);
        return view('image.detail', compact('image'));
    }

    /**
     * Download an image file
     */
    public function download($id)
    {
        $image = Image::findOrFail($id);

        // Determine the file path
        if (str_starts_with($image->path, 'images/')) {
            // File in public/images/
            $filePath = public_path($image->path);
        } else {
            // File in storage/app/public/
            $filePath = storage_path('app/public/' . $image->path);
        }

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        $fileName = $image->title . '.' . pathinfo($filePath, PATHINFO_EXTENSION);

        return response()->download($filePath, $fileName);
    }

    /**
     * Show the form for creating a new image (Admin only)
     */
    public function create(): View
    {
        // Admin check
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        return view('admin.galeri.create');
    }

    /**
     * Store a newly uploaded image (Admin only)
     */
    public function store(Request $request)
    {
        // Admin check
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string|max:1000',
        ]);

        $imagePath = $request->file('image')->store('images', 'public');

        Image::create([
            'title' => $request->title,
            'path' => $imagePath,
            'description' => $request->description,
        ]);

        return redirect()->route('gallery')->with('success', 'Gambar berhasil ditambahkan!');
    }

    /**
     * Show the form for editing an image (Admin only)
     */
    public function edit($id): View
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $image = Image::findOrFail($id);
        return view('admin.galeri.edit', compact('image'));
    }

    /**
     * Update an image (Admin only)
     */
    public function update(Request $request, $id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $image = Image::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string|max:1000',
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
        ];

        if ($request->hasFile('image')) {
            // Delete old file
            if ($image->path && Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
            }
            $data['path'] = $request->file('image')->store('images', 'public');
        }

        $image->update($data);

        return redirect()->route('gallery')->with('success', 'Gambar berhasil diperbarui!');
    }

    /**
     * Delete an image (Admin only)
     */
    public function destroy($id)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $image = Image::findOrFail($id);

        // Delete the file
        if ($image->path && Storage::disk('public')->exists($image->path)) {
            Storage::disk('public')->delete($image->path);
        }

        $image->delete();

        return redirect()->route('gallery')->with('success', 'Gambar berhasil dihapus!');
    }
}
