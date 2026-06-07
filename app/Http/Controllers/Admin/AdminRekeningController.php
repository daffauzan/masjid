<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RekeningTujuan;
use Illuminate\Http\Request;

class AdminRekeningController extends Controller
{
    public function index()
    {
        $rekeningList = RekeningTujuan::query()
            ->orderByDesc('is_active')
            ->orderBy('bank')
            ->orderBy('no_rekening')
            ->paginate(15);

        return view('admin.rekening.index', compact('rekeningList'));
    }

    public function create()
    {
        return view('admin.rekening.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bank' => 'required|string|max:100',
            'no_rekening' => 'required|string|max:50|unique:rekening_tujuan,no_rekening',
            'nama_pemilik' => 'required|string|max:100',
            'is_active' => 'required|boolean',
        ]);

        RekeningTujuan::create($validated);

        return redirect()
            ->route('admin.rekening.index')
            ->with('success', 'No rekening berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $rekening = RekeningTujuan::findOrFail($id);
        return view('admin.rekening.edit', compact('rekening'));
    }

    public function update(Request $request, $id)
    {
        $rekening = RekeningTujuan::findOrFail($id);

        $validated = $request->validate([
            'bank' => 'required|string|max:100',
            'no_rekening' => 'required|string|max:50|unique:rekening_tujuan,no_rekening,' . $id,
            'nama_pemilik' => 'required|string|max:100',
            'is_active' => 'required|boolean',
        ]);

        $rekening->update($validated);

        return redirect()
            ->route('admin.rekening.index')
            ->with('success', 'No rekening berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $rekening = RekeningTujuan::findOrFail($id);
        $rekening->delete();

        return redirect()
            ->route('admin.rekening.index')
            ->with('success', 'No rekening berhasil dihapus.');
    }
}