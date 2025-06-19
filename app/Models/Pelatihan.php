<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pelatihan extends Model
{
    use HasFactory;

    // Tambahkan 'tanggal_pelatihan' di sini
    protected $fillable = [
        'nama_pelatihan',
        'kode_pelatihan',
        'tanggal_pelatihan',
        'deskripsi',
        'lokasi',
        'nama_instruktur',
        'kuota',
        'status',
    ];

    // Memberitahu Laravel agar mengubah kolom ini menjadi objek Tanggal (Carbon)
    protected $casts = [
        'tanggal_pelatihan' => 'date:Y-m-d',
    ];

    public function pesertas(): HasMany
    {
        return $this->hasMany(Peserta::class);
    }
}