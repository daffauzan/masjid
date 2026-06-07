<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DetectController extends Controller
{
    public function index()
    {
        return view('admin.deteksi.deteksi');
    }

    public function predict(Request $request)
    {
        $request->validate([
            'image' => 'required|image'
        ]);

        $response = Http::attach(
            'file',
            file_get_contents(
                $request->file('image')->path()
            ),
            $request->file('image')->getClientOriginalName()
        )->post('http://127.0.0.1:8080/predict');

        $data = $response->json();

        return response()->json([
            'prediction' => $data['prediction'],
            'confidence' => $data['confidence'],
        ]);
    }
}
