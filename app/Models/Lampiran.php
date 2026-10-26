<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lampiran extends Model
{
    protected $table = 'lampiran';

    protected $fillable = ['pengajuan_id', 'nama_asli', 'path'];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }
}
