<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class adminuserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = User::all();
        return view('admin.user.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string',
            'email'    => 'required|string|email|unique:users',
            'no_telp'  => 'required|string|max:20'
        ]);

        User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),   // HASH PASSWORD!!!
            'email'    => $request->email,
            'no_telp'  => $request->no_telp,
        ]);

        return redirect()->route('admin.user.index')
                         ->with('success','Data user berhasil disimpan');
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
        $data = User::findOrFail($id);
        return view('admin.user.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email'    => 'required|string|email|unique:users,email,'.$id,
            'no_telp'  => 'required|string|max:20'
        ]);

        $data = User::findOrFail($id);

        // Jika password ingin diubah
        if($request->password != null){
            $data->password = Hash::make($request->password);
        }

        $data->username = $request->username;
        $data->email    = $request->email;
        $data->no_telp  = $request->no_telp;
        $data->save();

        return redirect()->route('admin.user.index')
                         ->with('success','Data user berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        User::destroy($id);

        return redirect()->route('admin.user.index')
                         ->with('success','Data user berhasil dihapus');
    }
}
