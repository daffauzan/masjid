<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use App\Models\Zakat;
use Illuminate\Support\Facades\Auth;

class adminzakat extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $zakat = Zakat::with('user')->orderBy('id', 'DESC')->get();
        return view('admin.zakat.index', compact('zakat'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        return view('admin.zakat.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_user'     => 'required',
            'nama_zakat'  => 'required',
            'kategori'    => 'required|in:fitrah,maal',
            'keterangan'  => 'required',
            'jumlah'      => 'required|numeric',
        ]);

        Zakat::create([
            'id_user'     => $request->id_user,
            'admin_id'    => Auth::guard('admin')->id(),
            'nama_zakat'  => $request->nama_zakat,
            'kategori'    => $request->kategori,
            'keterangan'  => $request->keterangan,
            'jumlah'      => $request->jumlah,
            'tanggal'     => now(),
        ]);

        return redirect()->route('zakat.index')->with('success', 'Data zakat berhasil ditambahkan');
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
        $users = User::all();

        return view('admin.zakat.edit', compact('zakat','users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'id_user'     => 'required',
            'nama_zakat'  => 'required',
            'kategori'    => 'required|in:fitrah,maal',
            'keterangan'  => 'required',
            'jumlah'      => 'required|numeric',
        ]);

        $zakat = Zakat::findOrFail($id);

        $zakat->update([
            'id_user'     => $request->id_user,
            'admin_id'    => Auth::guard('admin')->id(),
            'nama_zakat'  => $request->nama_zakat,
            'kategori'    => $request->kategori,
            'keterangan'  => $request->keterangan,
            'jumlah'      => $request->jumlah,
            'tanggal'     => now(),
        ]);

        return redirect()->route('zakat.index')->with('success', 'Data zakat berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $zakat = Zakat::findOrFail($id);
        $zakat->delete();

        return redirect()->route('zakat.index')->with('success', 'Data zakat berhasil dihapus');
    }
}
