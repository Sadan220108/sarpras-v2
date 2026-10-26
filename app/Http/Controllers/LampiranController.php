<?php

namespace App\Http\Controllers;

use App\Models\Lampiran;
use Illuminate\Support\Facades\Storage;

class LampiranController extends Controller
{
    // Lampiran disimpan di disk privat, jadi hanya pemiliknya yang bisa mengunduh lewat sini
    public function unduh(Lampiran $lampiran)
    {
        abort_unless($lampiran->pengajuan->user_id === auth()->id(), 403, 'Anda tidak punya akses ke berkas ini.');
        abort_unless(Storage::disk('local')->exists($lampiran->path), 404);

        return Storage::disk('local')->download($lampiran->path, $lampiran->nama_asli);
    }
}
