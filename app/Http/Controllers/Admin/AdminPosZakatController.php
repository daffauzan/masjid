<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\assessment as Assessment;
use App\Models\RekeningTujuan;
use App\Models\user as User;
use App\Models\zakat as Zakat;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class AdminPosZakatController extends Controller
{
    // Tarif default zakat fitrah per jiwa (rupiah)
    const TARIF_FITRAH_DEFAULT = 45000;
    private const PEGADAIAN_API_URL = 'https://logam-mulia-api.iamutaki.workers.dev/api/prices/pegadaian';

    public function index()
    {
        $transaksi = Transaksi::with('zakat')->latest()->limit(30)->get();
        $muzakkiUsers = User::where('role', 'user')->orderBy('nama')->get(['id', 'nama']);
        $pegadaianGoldPrice = $this->fetchPegadaianGoldPrice();
        $latestAssessments = Assessment::query()
            ->whereIn('user_id', $muzakkiUsers->pluck('id'))
            ->latest('created_at')
            ->get()
            ->unique('user_id')
            ->keyBy('user_id');

        $rekeningTujuan = RekeningTujuan::query()
            ->where('is_active', true)
            ->orderBy('bank')
            ->orderBy('no_rekening')
            ->get(['id', 'bank', 'no_rekening', 'nama_pemilik']);

        $totalHari      = Transaksi::whereDate('created_at', today())->where('status', 'paid')->count();
        $totalHariIni   = Transaksi::whereDate('created_at', today())->where('status', 'paid')->sum('jumlah_bayar');
        $totalFitrah    = Zakat::where('kategori', 'fitrah')->whereDate('created_at', today())->sum('jumlah_jiwa');
        $totalMaal      = Zakat::where('kategori', 'maal')->whereDate('created_at', today())->sum('jumlah');

        return view('admin.pos.zakat.index', compact(
            'transaksi',
            'muzakkiUsers',
            'pegadaianGoldPrice',
            'latestAssessments',
            'rekeningTujuan',
            'totalHari',
            'totalHariIni',
            'totalFitrah',
            'totalMaal'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_user'           => 'required|integer|exists:users,id',
            'kategori'          => 'required|in:fitrah,maal',
            'jumlah_jiwa'       => 'nullable|integer|min:1',
            'harga_per_jiwa'    => 'nullable|numeric|min:0',
            'jumlah_harta'      => 'nullable|numeric|min:0',
            'rincian_maal'      => 'nullable|string|max:1000',
            'jumlah_zakat'      => 'nullable|numeric|min:0',
            'metode_pembayaran'  => 'required|in:tunai,transfer',
            'rekening_tujuan_id' => 'required_if:metode_pembayaran,transfer|nullable|integer|exists:rekening_tujuan,id',
            'jumlah_bayar'      => 'required|numeric|min:0',
            'keterangan'        => 'nullable|string|max:500',
        ]);

        $muzakki = User::where('role', 'user')->find($validated['id_user']);

        if (!$muzakki) {
            return back()->withErrors([
                'id_user' => 'Muzakki yang dipilih tidak valid atau bukan role user.',
            ])->withInput();
        }

        $latestAssessment = Assessment::query()
            ->where('user_id', $muzakki->id)
            ->latest('created_at')
            ->first();

        if (!$latestAssessment) {
            return back()->withErrors([
                'id_user' => 'Muzakki belum memiliki assessment. Isi assessment user terlebih dahulu.',
            ])->withInput();
        }

        if ($validated['kategori'] === 'fitrah' && $latestAssessment->fitrah_paid_at) {
            return back()->withErrors([
                'kategori' => 'Zakat fitrah pada assessment terbaru user ini sudah lunas.',
            ])->withInput();
        }

        if ($validated['kategori'] === 'maal' && $latestAssessment->maal_paid_at) {
            return back()->withErrors([
                'kategori' => 'Zakat mal pada assessment terbaru user ini sudah lunas.',
            ])->withInput();
        }

        $assessmentJiwa = max(1, (int) ($latestAssessment->jumlah_jiwa_fitrah ?? 1));
        $assessmentBerasPerKg = (float) ($latestAssessment->harga_beras_per_kg ?? 0);
        $assessmentHargaPerJiwa = round($assessmentBerasPerKg * 2.5, 2);
        $assessmentHargaEmasPerGram = (float) ($latestAssessment->harga_emas_per_gram ?? 0);
        $assessmentNilaiEmas = (float) ($latestAssessment->nilai_emas_rupiah ?? 0);
        $pegadaianGoldPrice = $this->fetchPegadaianGoldPrice();
        $effectiveGoldPrice = $pegadaianGoldPrice ?? $assessmentHargaEmasPerGram;
        $nilaiKonversiMaal = $effectiveGoldPrice > 0
            ? round((float) ($latestAssessment->emas_gram ?? 0) * $effectiveGoldPrice, 2)
            : $assessmentNilaiEmas;

        $rincianAssessmentFitrah = 'Beras: Rp ' . number_format($assessmentBerasPerKg, 0, ',', '.')
            . '/kg | Jumlah Jiwa: ' . $assessmentJiwa;
        $rincianAssessmentMaal = 'Emas/gram: Rp ' . number_format($effectiveGoldPrice, 0, ',', '.')
            . ' | Beras/kg: Rp ' . number_format($assessmentBerasPerKg, 0, ',', '.');

        $nomorTransaksi = 'ZKT-' . now()->format('YmdHis') . '-' . strtoupper(substr(uniqid(), -5));

        $finalJumlahZakat = 0;
        $finalJumlahJiwa = null;

        if ($validated['kategori'] === 'fitrah') {
            $finalJumlahJiwa = $assessmentJiwa;
            $finalJumlahZakat = $assessmentJiwa * $assessmentHargaPerJiwa;

            $keteranganDetail = "Fitrah (assessment): {$assessmentJiwa} jiwa × Rp "
                . number_format($assessmentHargaPerJiwa, 0, ',', '.')
                . ' | ' . $rincianAssessmentFitrah;
        } else {
            $finalJumlahZakat = round($nilaiKonversiMaal * 0.025, 2);

            $keteranganDetail = 'Maal (assessment): ' . $rincianAssessmentMaal;

            $keteranganDetail .= ' | Nilai konversi aset: Rp '
                . number_format($nilaiKonversiMaal, 0, ',', '.');

            $keteranganDetail .= ' | Tagihan zakat otomatis (2,5%): Rp '
                . number_format($finalJumlahZakat, 0, ',', '.');
        }

        if (!empty($validated['keterangan'])) {
            $keteranganDetail .= " | " . $validated['keterangan'];
        }

        $finalJumlahBayar = (float) $validated['jumlah_bayar'];

        if ($validated['metode_pembayaran'] === 'transfer') {
            $rekening = RekeningTujuan::find($validated['rekening_tujuan_id']);

            if (!$rekening || !$rekening->is_active) {
                return back()->withErrors([
                    'rekening_tujuan_id' => 'Rekening tujuan transfer tidak tersedia.',
                ])->withInput();
            }

            $bankTransfer = $rekening->bank;
            $noRekening = $rekening->no_rekening;
            $namaRekening = $rekening->nama_pemilik;

            $keteranganDetail .= ' | Transfer: ' . $bankTransfer
                . ' - ' . $noRekening
                . ' a/n ' . $namaRekening;

            // Transfer mengikuti total tagihan otomatis.
            $finalJumlahBayar = $finalJumlahZakat;
        } elseif ($finalJumlahBayar < $finalJumlahZakat) {
            return back()->withErrors([
                'jumlah_bayar' => 'Pembayaran tunai tidak boleh kurang dari total tagihan zakat.',
            ])->withInput();
        }

        $transaksiRecord = DB::transaction(function () use (
            $muzakki,
            $validated,
            $finalJumlahZakat,
            $finalJumlahJiwa,
            $keteranganDetail,
            $finalJumlahBayar,
            $nomorTransaksi,
            $latestAssessment
        ) {
            $zakatRecord = Zakat::create([
                'id_user'     => $muzakki->id,
                'nama_zakat'  => $muzakki->nama,
                'kategori'    => $validated['kategori'],
                'jumlah'      => $finalJumlahZakat,
                'jumlah_jiwa' => $validated['kategori'] === 'fitrah' ? $finalJumlahJiwa : null,
                'keterangan'  => $keteranganDetail,
                'admin_id'    => auth()->id(),
                'tanggal'     => now()->toDateString(),
            ]);

            $statusPembayaran = $validated['metode_pembayaran'] === 'tunai'
                ? 'paid'
                : 'pending';

            $tanggalBayar = $validated['metode_pembayaran'] === 'tunai'
                ? now()
                : null;

            $transaksiRecord = Transaksi::create([
                'zakat_id'          => $zakatRecord->id,
                'id_user'           => $muzakki->id,
                'admin_id'          => auth()->id(),
                'jumlah_bayar'      => $finalJumlahBayar,
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'status'            => $statusPembayaran,
                'nomor_transaksi'   => $nomorTransaksi,
                'tanggal_bayar'     => $tanggalBayar,
                'keterangan'        => $keteranganDetail,
            ]);

            if ($validated['metode_pembayaran'] === 'tunai') {

                if ($validated['kategori'] === 'fitrah') {

                    $latestAssessment->forceFill([
                        'fitrah_paid_at' => now(),
                    ])->save();

                } else {

                    $latestAssessment->forceFill([
                        'maal_paid_at' => now(),
                    ])->save();
                }
            }

            return $transaksiRecord;
        });

        return redirect()
            ->route('admin.pos.zakat.receipt', $transaksiRecord->id)
            ->with('success', 'Transaksi zakat berhasil diproses.');
    }

    public function confirm(int $id)
    {
        $transaksi = Transaksi::with('zakat')->findOrFail($id);

        if ($transaksi->status === 'paid') {
            return back()->with('error', 'Transaksi sudah lunas.');
        }

        DB::transaction(function () use ($transaksi) {

            $transaksi->update([
                'status' => 'paid',
                'tanggal_bayar' => now(),
            ]);

            $assessment = Assessment::where('user_id', $transaksi->id_user)
                ->latest('created_at')
                ->first();

            if ($assessment && $transaksi->zakat) {

                if ($transaksi->zakat->kategori === 'fitrah') {

                    $assessment->update([
                        'fitrah_paid_at' => now(),
                    ]);

                } else 
                {
                    $assessment->update([
                        'maal_paid_at' => now(),
                    ]);
                }
            }
        });

        return back()->with('success', 'Pembayaran transfer berhasil dikonfirmasi.');
    }

    public function receipt(int $id)
    {
        $transaksi = Transaksi::with('zakat')->findOrFail($id);
        return view('admin.pos.zakat.receipt', compact('transaksi'));
    }

    public function cancel(int $id)
    {
        $transaksi = Transaksi::with('zakat')->findOrFail($id);

        if ($transaksi->status === 'paid') {
            return back()->with(
                'error',
                'Transaksi yang sudah lunas tidak dapat dibatalkan.'
            );
        }

        // OPTIONAL:
        // jika sudah cancelled juga jangan diproses lagi
        if ($transaksi->status === 'cancelled') {
            return back()->with(
                'error',
                'Transaksi sudah dibatalkan sebelumnya.'
            );
        }

        DB::transaction(function () use ($transaksi) {

            $zakat = $transaksi->zakat;

            $assessment = Assessment::where('user_id', $transaksi->id_user)
                ->latest('created_at')
                ->first();

            if ($zakat && $assessment) {

                if ($zakat->kategori === 'fitrah') {

                    $assessment->forceFill([
                        'fitrah_paid_at' => null,
                    ])->save();

                } else {

                    $assessment->forceFill([
                        'maal_paid_at' => null,
                    ])->save();
                }
            }

            $transaksi->update([
                'status' => 'cancelled',
            ]);
        });

        return redirect()
            ->route('admin.pos.zakat.index')
            ->with('success', 'Transaksi berhasil dibatalkan.');
    }

    private function fetchPegadaianGoldPrice(): ?float
    {
        try {
            $response = Http::timeout(8)->acceptJson()->get(self::PEGADAIAN_API_URL);
            if (!$response->successful()) {
                return null;
            }

            $payload = $response->json();
            $rows = data_get($payload, 'data', []);

            if (!is_array($rows) || count($rows) === 0) {
                return null;
            }

            $firstRow = $rows[0];
            if (!is_array($firstRow)) {
                return null;
            }

            $price = data_get($firstRow, 'sellPrice');
            return is_numeric($price) ? (float) $price : null;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
