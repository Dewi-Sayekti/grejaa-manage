<?php

namespace App\Http\Controllers;

use App\Models\Keuangan;
use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    public function index()
    {
        $keuangan = Keuangan::all();
        return view('keuangan.index', compact('keuangan'));
    }

    public function create()
    {
        return view('keuangan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori' => 'required|string',
            'jumlah' => 'required|numeric',
            'deskripsi' => 'nullable|string',
            'tanggal' => 'required|date',
        ]);

        Keuangan::create($validated);

        return redirect()->route('keuangan.index')->with('success', 'Data keuangan berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $keuangan = Keuangan::find($id);
        return view('keuangan.edit', compact('keuangan'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'kategori' => 'required|string',
            'jumlah' => 'required|numeric',
            'deskripsi' => 'nullable|string',
            'tanggal' => 'required|date',
        ]);

        $keuangan = Keuangan::find($id);
        $keuangan->update($validated);

        return redirect()->route('keuangan.index')->with('success', 'Data keuangan berhasil diperbarui!');
    }
}
