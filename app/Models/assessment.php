<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class assessment extends Model
{
    protected $table = 'assessments';

    protected $fillable = [
        'user_id',
        'gaji',
        'tabungan',
        'emas_gram',
        'hutang',
        'harga_emas_per_gram',
        'harga_beras_per_kg',
        'jumlah_jiwa_fitrah',
        'nilai_emas_rupiah',
        'total_harta_bersih',
        'nisab_mal_rupiah',
        'wajib_zakat_mal',
        'nominal_zakat_mal',
        'nominal_zakat_fitrah',
        'fitrah_paid_at',
        'maal_paid_at',
        'catatan',
    ];

    protected $casts = [
        'wajib_zakat_mal' => 'boolean',
        'fitrah_paid_at' => 'datetime',
        'maal_paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(user::class, 'user_id');
    }
}
