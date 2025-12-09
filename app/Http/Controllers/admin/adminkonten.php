<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\konten;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class adminkonten extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Konten::orderBy('id','desc')->get();
        return view('admin.konten.index', compact('data'));
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
            'judul'     => 'required',
            'konten'    => 'required',
            'kategori'  => 'required|in:informasi,dakwah',
            'gambar'    => 'required|mimes:jpg,jpeg,png',
            'file'      => 'nullable|mimes:pdf',
        ]);

        $gambar = $request->file('gambar');
        $namaGambar = time().'_'.$gambar->getClientOriginalName();
        $gambar->move(public_path('gambar'), $namaGambar);

        $namaFile = null;
        if ($request->file('file')) {
            $file = $request->file('file');
            $namaFile = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('file'), $namaFile);
        }

        $slug = Str::slug($request->kategori.' '.$request->judul);

        Konten::create([
            'judul'     => $request->judul,
            'konten'    => $request->konten,
            'kategori'  => $request->kategori,
            'gambar'    => $namaGambar,
            'file'      => $namaFile,
            'slug'      => $slug,
            'id_admin'  => auth()->guard('admin')->user()->id,
            'tanggal'   => now(),
        ]);

        return redirect()->route('konten.index')
                         ->with('success','Konten Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = konten::findOrFail($id);
        return view('admin.konten.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'judul'     => 'required',
            'konten'    => 'required',
            'kategori'  => 'required|in:informasi,dakwah',
            'gambar'    => 'nullable|mimes:jpg,jpeg,png',
            'file'      => 'nullable|mimes:pdf,doc,docx,xlsx,ppt,pptx',
            'tanggal'   => 'required|date',
        ]);

        $data = Konten::findOrFail($id);
        $slug = Str::slug($request->kategori.' '.$request->judul);

        if ($request->file('gambar')) {
            unlink(public_path('gambar/'.$data->gambar));

            $gambar = $request->file('gambar');
            $namaGambar = time().'_'.$gambar->getClientOriginalName();
            $gambar->move(public_path('gambar'), $namaGambar);
            $data->gambar = $namaGambar;
        }

        if ($request->file('file')) {

            if ($data->file && file_exists(public_path('file/'.$data->file))) {
                unlink(public_path('file/'.$data->file));
            }

            $file = $request->file('file');
            $namaFile = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('file'), $namaFile);
            $data->file = $namaFile;
        }

        $data->judul     = $request->judul;
        $data->konten    = $request->konten;
        $data->kategori  = $request->kategori;
        $data->slug      = $slug;
        $data->tanggal   = $request->tanggal;

        $data->save();

        return redirect()->route('konten.index')
                         ->with('success','Konten berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Konten::findOrFail($id);

        if ($data->gambar && file_exists(public_path('gambar/'.$data->gambar))) {
            unlink(public_path('gambar/'.$data->gambar));
        }

        if ($data->file && file_exists(public_path('file/'.$data->file))) {
            unlink(public_path('file/'.$data->file));
        }

        $data->delete();

        return redirect()->route('konten.index')
                         ->with('success','Konten berhasil dihapus');
    }
}
