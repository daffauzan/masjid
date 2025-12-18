<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Konten;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class adminkonten extends Controller
{
    private function getKontenByKategori(){
        return [
            'informasi' => Konten::where('kategori', 'informasi')->latest()->get(),
            'dakwah' => Konten::where('kategori', 'dakwah')->latest()->get(),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $konten = Konten::latest()->get();
        return view('admin.konten.index', compact('konten'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.konten.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul'    => 'required|string|max:255',
            'konten'   => 'required',
            'kategori' => 'required|in:informasi,dakwah',
            'gambar'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'file'     => 'nullable|mimes:pdf|max:2048',
        ]);

        $gambarPath = null;
        if ($request->hasFile('gambar')){
            $gambarPath = $request->file('gambar')
                ->store('konten/gambar', 'public');
        }

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('konten/pdf', 'public');
        }

        Konten::create([
            'judul'     => $request->judul,
            'konten'    => $request->konten,
            'kategori'  => $request->kategori,
            'gambar'    => $gambarPath,
            'file'      => $filePath,
            'id_admin'  => auth('admin')->id(),
        ]);

        return redirect()->route('admin.konten.index')
                         ->with('success', 'Konten berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $konten = Konten::findOrFail($id);
        return view('admin.konten.show', compact('konten'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $konten = Konten::findOrFail($id);
        return view('admin.konten.edit', compact('konten'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $konten = Konten::findOrFail($id);

        $request->validate([
            'judul'    => 'required|string|max:255',
            'konten'   => 'required',
            'kategori' => 'required|in:informasi,dakwah',
            'gambar'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'file'     => 'nullable|mimes:pdf|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            if ($konten->gambar) {
                Storage::disk('public')->delete($konten->gambar);
            }
            $konten->gambar = $request->file('gambar')
                ->store('konten/gambar', 'public');
        }

        if ($request->hasFile('file')) {
            if ($konten->file) {
                Storage::disk('public')->delete($konten->file);
            }
            $konten->file = $request->file('file')
                ->store('konten/pdf', 'public');
        }

        $konten->update([
            'judul'    => $request->judul,
            'konten'   => $request->konten,
            'kategori' => $request->kategori,
        ]);

        return redirect()->route('admin.konten.index')
            ->with('success', 'Konten berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $konten = Konten::findOrFail($id);

        if ($konten->file) {
            Storage::disk('public')->delete($konten->file);
        }

        $konten->delete();

        return redirect()->route('admin.konten.index')
                         ->with('success', 'Konten berhasil dihapus');
    }
}
