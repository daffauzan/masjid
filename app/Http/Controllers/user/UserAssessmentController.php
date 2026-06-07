<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use Illuminate\Http\Request;

class UserAssessmentController extends Controller
{
    private const HARGA_EMAS_PER_GRAM = 1900000;
    private const HARGA_BERAS_PER_KG = 16000;

    public function index()
    {
        $assessments = Assessment::where('user_id', auth()->id())->latest()->paginate(10);

        return view('user.assessment.index', compact('assessments'));
    }

    public function create()
    {
        return view('user.assessment.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'gaji' => 'required|numeric|min:0',
            'tabungan' => 'required|numeric|min:0',
            'emas_gram' => 'required|numeric|min:0',
            'hutang' => 'required|numeric|min:0',
            'jumlah_jiwa_fitrah' => 'required|integer|min:1',
            'catatan' => 'nullable|string|max:1000',
        ]);

        $hargaEmasPerGram = self::HARGA_EMAS_PER_GRAM;
        $hargaBerasPerKg = self::HARGA_BERAS_PER_KG;

        $nilaiEmasRupiah = (float) $validated['emas_gram'] * (float) $hargaEmasPerGram;
        $totalHartaBersih = ((float) $validated['gaji'] + (float) $validated['tabungan'] + $nilaiEmasRupiah) - (float) $validated['hutang'];
        $nisabMalRupiah = 85 * (float) $hargaEmasPerGram;

        $wajibZakatMal = $nisabMalRupiah > 0 && $totalHartaBersih >= $nisabMalRupiah;
        $nominalZakatMal = $wajibZakatMal ? round($totalHartaBersih * 0.025, 2) : 0;

        $nominalZakatFitrah = round((int) $validated['jumlah_jiwa_fitrah'] * 2.5 * (float) $hargaBerasPerKg, 2);

        Assessment::create([
            'user_id' => auth()->id(),
            'gaji' => $validated['gaji'],
            'tabungan' => $validated['tabungan'],
            'emas_gram' => $validated['emas_gram'],
            'hutang' => $validated['hutang'],
            'harga_emas_per_gram' => $hargaEmasPerGram,
            'harga_beras_per_kg' => $hargaBerasPerKg,
            'jumlah_jiwa_fitrah' => $validated['jumlah_jiwa_fitrah'],
            'nilai_emas_rupiah' => $nilaiEmasRupiah,
            'total_harta_bersih' => $totalHartaBersih,
            'nisab_mal_rupiah' => $nisabMalRupiah,
            'wajib_zakat_mal' => $wajibZakatMal,
            'nominal_zakat_mal' => $nominalZakatMal,
            'nominal_zakat_fitrah' => $nominalZakatFitrah,
            'catatan' => $validated['catatan'] ?? null,
        ]);

        return redirect()->route('user.assessment.index')->with('success', 'Assessment berhasil disimpan.');
    }
}
