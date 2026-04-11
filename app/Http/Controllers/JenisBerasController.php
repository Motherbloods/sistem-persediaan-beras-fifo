<?php

namespace App\Http\Controllers;

use App\Models\JenisBeras;
use Illuminate\Http\Request;

class JenisBerasController extends Controller
{
    public function index()
    {
        $data = JenisBeras::latest()->paginate(10);

        return view('jenis-beras.index', compact('data'));
    }

    public function create()
    {
        return view('jenis-beras.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_beras' => 'required|string|max:20|unique:jenis_beras,kode_beras',
            'nama_beras' => 'required|string|max:255',
            'satuan' => 'required|string|max:20',
            'stok_minimum' => 'required|numeric|min:0',
            'harga_per_satuan' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        JenisBeras::create($validated);

        return redirect()->route('jenis-beras.index')
            ->with('success', 'Jenis beras berhasil ditambahkan.');
    }

    public function show(JenisBeras $jenisBeras)
    {
        $jenisBeras->load([
            'stokMasuks' => fn($q) => $q->latest()->limit(10),
            'stokKeluars' => fn($q) => $q->latest()->limit(10),
        ]);

        return view('jenis-beras.show', compact('jenisBeras'));
    }

    public function edit(JenisBeras $jenisBeras)
    {
        return view('jenis-beras.form', compact('jenisBeras'));
    }

    public function update(Request $request, JenisBeras $jenisBeras)
    {
        $validated = $request->validate([
            'kode_beras' => 'required|string|max:20|unique:jenis_beras,kode_beras,' . $jenisBeras->id,
            'nama_beras' => 'required|string|max:255',
            'satuan' => 'required|string|max:20',
            'stok_minimum' => 'required|numeric|min:0',
            'harga_per_satuan' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $jenisBeras->update($validated);

        return redirect()->route('jenis-beras.index')
            ->with('success', 'Jenis beras berhasil diperbarui.');
    }

    public function destroy(JenisBeras $jenisBeras)
    {
        // Cek apakah masih ada stok tersedia sebelum menghapus
        if ($jenisBeras->stok_saat_ini > 0) {
            return back()->with('error', 'Tidak dapat menghapus jenis beras yang masih memiliki stok.');
        }

        $jenisBeras->delete();

        return redirect()->route('jenis-beras.index')
            ->with('success', 'Jenis beras berhasil dihapus.');
    }
}