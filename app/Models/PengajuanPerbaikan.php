<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanPerbaikan extends Model
{
    protected $table = 'pengajuan_perbaikan';

    protected $fillable = ['pengajuan_id', 'nama_sarana', 'kerusakan', 'lokasi', 'biaya', 'keterangan'];
}
