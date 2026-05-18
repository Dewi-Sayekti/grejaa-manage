<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceContentController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category', 'jadwal_ibadah');
        $contents = ServiceContent::where('category', $category)->get();
        return view('admin.services.index', compact('contents', 'category'));
    }

    public function create(Request $request)
    {
        $category = $request->query('category', 'jadwal_ibadah');
        return view('admin.services.create', compact('category'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|in:jadwal_ibadah,kelompok_kecil,sekolah_minggu,musik',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_link' => 'nullable|string',
            'video_file' => 'nullable|mimes:mp4,mov,ogg,qt|max:20000',
        ]);

        $data = $request->only(['category', 'title', 'description', 'video_link']);

        if ($request->hasFile('video_file')) {
            $path = $request->file('video_file')->store('service_videos', 'public');
            $data['video_path'] = 'storage/' . $path;
        }

        ServiceContent::create($data);

        return redirect()->route('admin.services.index', ['category' => $data['category']])->with('success', 'Konten Pelayanan berhasil ditambahkan.');
    }

    public function edit(ServiceContent $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, ServiceContent $service)
    {
        $request->validate([
            'category' => 'required|in:jadwal_ibadah,kelompok_kecil,sekolah_minggu,musik',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_link' => 'nullable|string',
            'video_file' => 'nullable|mimes:mp4,mov,ogg,qt|max:20000',
        ]);

        $data = $request->only(['category', 'title', 'description', 'video_link']);

        if ($request->hasFile('video_file')) {
            // Delete old video
            if ($service->video_path && str_starts_with($service->video_path, 'storage/')) {
                Storage::disk('public')->delete(str_replace('storage/', '', $service->video_path));
            }

            $path = $request->file('video_file')->store('service_videos', 'public');
            $data['video_path'] = 'storage/' . $path;
        }

        $service->update($data);

        return redirect()->route('admin.services.index', ['category' => $data['category']])->with('success', 'Konten Pelayanan berhasil diperbarui.');
    }

    public function destroy(ServiceContent $service)
    {
        if ($service->video_path && str_starts_with($service->video_path, 'storage/')) {
            Storage::disk('public')->delete(str_replace('storage/', '', $service->video_path));
        }
        
        $category = $service->category;
        $service->delete();

        return redirect()->route('admin.services.index', ['category' => $category])->with('success', 'Konten Pelayanan berhasil dihapus.');
    }
}
