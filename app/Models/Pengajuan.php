<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pengajuan extends Model
{
    protected $table = 'pengajuan';

    protected $fillable = ['nomor', 'tanggal', 'user_id', 'jabatan', 'unit'];

    protected function casts(): array
    {
        return ['tanggal' => 'date'];
    }

    public function pemohon()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(PengajuanItem::class);
    }

    public function perbaikan()
    {
        return $this->hasMany(PengajuanPerbaikan::class);
    }

    public function lampiran()
    {
        return $this->hasMany(Lampiran::class);
    }

    public function getTotalAttribute(): int
    {
        $a = $this->items->sum(fn ($i) => $i->biaya * $i->jumlah);
        $b = $this->perbaikan->sum('biaya');

        return (int) ($a + $b);
    }

    public function scopeCari(Builder $q, ?string $term): Builder
    {
        $term = trim((string) $term);
        if ($term === '') {
            return $q;
        }

        return $q->where(function (Builder $w) use ($term) {
            $w->where('nomor', 'like', "%{$term}%")
                ->orWhereHas('items', fn ($i) => $i->where('nama_barang', 'like', "%{$term}%"))
                ->orWhereHas('perbaikan', fn ($r) => $r->where('nama_sarana', 'like', "%{$term}%"));
        });
    }

    // Nomor surat 001/SARPRAS/X-2026. Harus dipanggil di dalam DB::transaction.
    // Urutan diambil dari tabel nomor_urut yang dikunci, jadi dua pengajuan
    // yang masuk bersamaan tidak akan mendapat nomor yang sama.
    public static function nomorBerikutnya(?Carbon $tgl = null): string
    {
        $tgl = $tgl ?? now();

        DB::table('nomor_urut')->insertOrIgnore(['tahun' => $tgl->year, 'terakhir' => 0]);

        $baris = DB::table('nomor_urut')->where('tahun', $tgl->year)->lockForUpdate()->first();
        $urut = $baris->terakhir + 1;

        DB::table('nomor_urut')->where('tahun', $tgl->year)->update(['terakhir' => $urut]);

        $romawi = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'][$tgl->month - 1];

        return sprintf('%03d/%s/%s-%d', $urut, config('sarpras.kode_unit', 'SARPRAS'), $romawi, $tgl->year);
    }
}
