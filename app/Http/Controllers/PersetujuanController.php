<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\Persetujuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersetujuanController extends Controller
{
    public function store(Request $request, Pengajuan $pengajuan)
    {
        $user = auth()->user();

        $request->validate([
            'keputusan'    => 'required|in:setuju,tolak',
            'catatan'      => 'nullable|string|max:1000',
            'hasil_survey' => 'nullable|string|max:2000',
        ]);

        if ($request->keputusan === 'tolak' && ! trim((string) $request->catatan)) {
            return back()->withInput()->withErrors(['catatan' => 'Alasan penolakan wajib diisi.']);
        }

        if ($user->role === 'koordinator' && $request->keputusan === 'setuju' && ! trim((string) $request->hasil_survey)) {
            return back()->withInput()->withErrors(['hasil_survey' => 'Hasil survey wajib diisi sebelum menyetujui.']);
        }

        DB::transaction(function () use ($request, $pengajuan, $user) {
            // Kunci baris agar dua klik bersamaan tidak memproses dua kali
            $p = Pengajuan::lockForUpdate()->findOrFail($pengajuan->id);

            // Hanya peran yang sedang bertugas yang boleh memutuskan
            if ($p->perluPeran() !== $user->role) {
                abort(403, 'Pengajuan ini bukan giliran Anda.');
            }

            Persetujuan::create([
                'pengajuan_id' => $p->id,
                'user_id'      => $user->id,
                'putaran'      => $p->putaran,
                'tahap'        => $user->role,
                'keputusan'    => $request->keputusan,
                'catatan'      => $request->catatan,
            ]);

            if ($user->role === 'koordinator' && $request->keputusan === 'setuju') {
                $p->hasil_survey = $request->hasil_survey;
            }

            $p->status = $request->keputusan === 'tolak' ? 'ditolak' : Pengajuan::BERIKUTNYA[$p->status];
            $p->save();
        });

        return redirect()->route('pengajuan.show', $pengajuan)
            ->with('sukses', $request->keputusan === 'setuju' ? 'Pengajuan disetujui.' : 'Pengajuan ditolak.');
    }
}
