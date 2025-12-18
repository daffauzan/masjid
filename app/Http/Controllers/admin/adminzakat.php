<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Zakat;

class adminzakat extends Controller
{

    private function getZakatData(){
        return Zakat::latest()->get();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $zakat = $this->getZakatData();
        return view('admin.zakat.index', compact('zakat'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.zakat.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_zakat' => 'required|string|max:255',
            'kategori'   => 'required|in:fitrah,maal',
            'jumlah'     => 'required|numeric|min:0',
            'tanggal'    => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        Zakat::create([
            'id_user'   => $request->id_user, // dari select user
            'admin_id'  => auth('admin')->id(),
            'nama_zakat'=> $request->nama_zakat,
            'kategori'  => $request->kategori,
            'keterangan'=> $request->keterangan,
            'jumlah'    => $request->jumlah,
            'tanggal'   => now()->toDateString(),
        ]);

        return redirect()->route('admin.zakat.index')
            ->with('success', 'Data zakat berhasil ditambahkan');
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
        $zakat = Zakat::findOrFail($id);
        return view('admin.zakat.edit', compact('zakat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $zakat = Zakat::findOrFail($id);

        $request->validate([
            'nama_zakat' => 'required|string|max:255',
            'kategori'   => 'required|in:fitrah,maal',
            'jumlah'     => 'required|numeric|min:0',
            'tanggal'    => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $zakat->update([
            'nama_zakat'=> $request->nama_zakat,
            'kategori'  => $request->kategori,
            'keterangan'=> $request->keterangan,
            'jumlah'    => $request->jumlah,
            'tanggal'   => $request->tanggal,
        ]);

        return redirect()->route('admin.zakat.index')
            ->with('success', 'Data zakat berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       //
    }
}
