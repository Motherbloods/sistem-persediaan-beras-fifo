<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $data = Supplier::latest()->paginate(10);

        return view('supplier.index', compact('data'));
    }

    public function create()
    {
        return view('supplier.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_supplier' => 'required|string|max:20|unique:suppliers,kode_supplier',
            'nama_supplier' => 'required|string|max:255',
            'pic' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        Supplier::create($validated);

        return redirect()->route('supplier.index')
            ->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function show(Supplier $supplier)
    {
        $supplier->load(['stokMasuks' => fn($q) => $q->with('jenisBeras')->latest()->limit(10)]);

        return view('supplier.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('supplier.form', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'kode_supplier' => 'required|string|max:20|unique:suppliers,kode_supplier,' . $supplier->id,
            'nama_supplier' => 'required|string|max:255',
            'pic' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $supplier->update($validated);

        return redirect()->route('supplier.index')
            ->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->stokMasuks()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus supplier yang sudah memiliki data transaksi.');
        }

        $supplier->delete();

        return redirect()->route('supplier.index')
            ->with('success', 'Supplier berhasil dihapus.');
    }
}