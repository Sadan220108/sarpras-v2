<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanItem extends Model
{
    protected $table = 'pengajuan_items';

    protected $fillable = ['pengajuan_id', 'nama_barang', 'spesifikasi', 'link', 'biaya', 'jumlah', 'keterangan'];
}
