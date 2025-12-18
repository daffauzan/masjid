<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Zakat;
use App\Models\konten;

class AdminDashboardController extends Controller
{
    private function getKontenData(){
        return [
            'informasi' => Konten::where('kategori', 'informasi')
                ->latest()
                ->take(5)
                ->get(),
            'dakwah' => Konten::where('kategori', 'dakwah')
                ->latest()
                ->take(5)
                ->get(),
        ];
    }

    private function getZakatData(){
        return Zakat::latest()->limit(10)->get();
    }
    
    public function index(){
        $konten = $this->getKontenData();
        $zakat = $this->getZakatData();

        return view('admin.index', compact('konten', 'zakat'));
    }
}
