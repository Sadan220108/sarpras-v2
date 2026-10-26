@php $f = fn ($k, $d = '') => old("items.$i.$k", $v[$k] ?? $d); @endphp
<div class="rowbox" data-row>
    <div class="g">
        <label class="f"><span>Nama barang</span><input name="items[{{ $i }}][nama_barang]" value="{{ $f('nama_barang') }}"></label>
        <label class="f"><span>Spesifikasi</span><input name="items[{{ $i }}][spesifikasi]" value="{{ $f('spesifikasi') }}" placeholder="Atau isi link di sebelah"></label>
        <label class="f"><span>Link marketplace</span><input name="items[{{ $i }}][link]" value="{{ $f('link') }}" placeholder="https://..."></label>
        <label class="f"><span>Estimasi biaya (per satuan)</span><input class="js-biaya" type="number" min="0" name="items[{{ $i }}][biaya]" value="{{ $f('biaya') }}"></label>
        <label class="f"><span>Jumlah</span><input class="js-jumlah" type="number" min="1" name="items[{{ $i }}][jumlah]" value="{{ $f('jumlah', 1) }}"></label>
        <label class="f"><span>Keterangan</span><input name="items[{{ $i }}][keterangan]" value="{{ $f('keterangan') }}"></label>
    </div>
    <button type="button" class="link danger" data-remove style="margin-top:8px">Hapus baris</button>
</div>
