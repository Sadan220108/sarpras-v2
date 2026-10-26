<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persetujuan extends Model
{
    protected $table = 'persetujuan';

    protected $fillable = ['pengajuan_id', 'user_id', 'putaran', 'tahap', 'keputusan', 'catatan'];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function pejabat()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
