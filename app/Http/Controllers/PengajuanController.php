<?php

namespace App\Http\Controllers;

use App\Http\Requests\PengajuanRequest;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

// Semua halaman di sini khusus pemohon, dan pemohon hanya melihat pengajuannya sendiri.
class PengajuanController extends Controller
{
    private function milikSaya()
    {
        return Pengajuan::where('user_id', auth()->id());
    }

    public function dashboard()
    {
        // Admin hanya mengelola akun
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.users.index');
        }

        $stat = [
            'total'     => $this->milikSaya()->count(),
            'bulan_ini' => $this->milikSaya()->whereYear('tanggal', now()->year)->whereMonth('tanggal', now()->month)->count(),
        ];

        $terbaru = $this->milikSaya()->with(['items', 'perbaikan'])->latest()->take(5)->get();

        return view('pengajuan.dashboard', compact('stat', 'terbaru'));
    }

    // Semua pengajuan milik pemohon, dengan pencarian dan pembagian halaman
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $list = $this->milikSaya()
            ->with(['items', 'perbaikan'])
            ->cari($q)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pengajuan.index', compact('list', 'q'));
    }

    public function create()
    {
        return view('pengajuan.form', [
            'pengajuan' => null,
            'items'     => [[]],
            'repairs'   => [],
        ]);
    }

    public function store(PengajuanRequest $request)
    {
        $user = auth()->user();

        $pengajuan = DB::transaction(function () use ($request, $user) {
            $tgl = now();

            $p = Pengajuan::create([
                'nomor'   => Pengajuan::nomorBerikutnya($tgl),
                'tanggal' => $tgl->toDateString(),
                'user_id' => $user->id,
                'jabatan' => $user->jabatan,
                'unit'    => $user->unit,
            ]);

            $this->simpanIsi($p, $request);

            return $p;
        });

        return redirect()->route('pengajuan.show', $pengajuan)
            ->with('sukses', 'Pengajuan '.$pengajuan->nomor.' tersimpan. Silakan cetak.');
    }

    // Memperbaiki isi (misalnya salah ketik). Nomor surat tetap.
    public function edit(Pengajuan $pengajuan)
    {
        $this->pastikanMilikSaya($pengajuan);
        $pengajuan->load(['items', 'perbaikan', 'lampiran']);

        return view('pengajuan.form', [
            'pengajuan' => $pengajuan,
            'items'     => $pengajuan->items->map(fn ($m) => Arr::only($m->toArray(), ['nama_barang', 'spesifikasi', 'link', 'biaya', 'jumlah', 'keterangan']))->values()->all(),
            'repairs'   => $pengajuan->perbaikan->map(fn ($m) => Arr::only($m->toArray(), ['nama_sarana', 'kerusakan', 'lokasi', 'biaya', 'keterangan']))->values()->all(),
        ]);
    }

    public function update(PengajuanRequest $request, Pengajuan $pengajuan)
    {
        $this->pastikanMilikSaya($pengajuan);

        DB::transaction(function () use ($request, $pengajuan) {
            $pengajuan->items()->delete();
            $pengajuan->perbaikan()->delete();

            foreach ($pengajuan->lampiran()->whereIn('id', (array) $request->input('hapus_lampiran', []))->get() as $l) {
                Storage::disk('local')->delete($l->path);
                $l->delete();
            }

            $this->simpanIsi($pengajuan, $request);
            $pengajuan->touch();
        });

        return redirect()->route('pengajuan.show', $pengajuan)->with('sukses', 'Perubahan tersimpan. Cetak ulang jika perlu.');
    }

    public function show(Pengajuan $pengajuan)
    {
        $this->pastikanMilikSaya($pengajuan);
        $pengajuan->load(['pemohon', 'items', 'perbaikan', 'lampiran']);

        return view('pengajuan.show', compact('pengajuan'));
    }

    // Halaman cetak: surat + Berita Acara Kerusakan + Berita Acara Survey
    public function cetak(Pengajuan $pengajuan)
    {
        $this->pastikanMilikSaya($pengajuan);
        $pengajuan->load(['pemohon', 'items', 'perbaikan', 'lampiran']);

        return view('pengajuan.cetak', compact('pengajuan'));
    }

    private function simpanIsi(Pengajuan $p, Request $request): void
    {
        foreach ((array) $request->input('items', []) as $i) {
            $p->items()->create(Arr::only($i, ['nama_barang', 'spesifikasi', 'link', 'biaya', 'jumlah', 'keterangan']));
        }

        foreach ((array) $request->input('repairs', []) as $r) {
            $p->perbaikan()->create(Arr::only($r, ['nama_sarana', 'kerusakan', 'lokasi', 'biaya', 'keterangan']));
        }

        foreach ($request->file('lampiran', []) as $berkas) {
            $p->lampiran()->create([
                'nama_asli' => $berkas->getClientOriginalName(),
                'path'      => $berkas->store("lampiran/{$p->id}", 'local'),
            ]);
        }
    }

    private function pastikanMilikSaya(Pengajuan $pengajuan): void
    {
        abort_unless($pengajuan->user_id === auth()->id(), 403, 'Anda tidak punya akses ke pengajuan ini.');
    }
}
