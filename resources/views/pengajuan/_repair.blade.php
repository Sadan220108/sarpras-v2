@php $f = fn ($k, $d = '') => old("repairs.$i.$k", $v[$k] ?? $d); @endphp
<div class="rowbox" data-row>
    <div class="g">
        <label class="f"><span>Nama sarana/prasarana</span><input name="repairs[{{ $i }}][nama_sarana]" value="{{ $f('nama_sarana') }}"></label>
        <label class="f"><span>Kerusakan</span><input name="repairs[{{ $i }}][kerusakan]" value="{{ $f('kerusakan') }}"></label>
        <label class="f"><span>Lokasi</span><input name="repairs[{{ $i }}][lokasi]" value="{{ $f('lokasi') }}"></label>
        <label class="f"><span>Estimasi biaya</span><input class="js-biaya" type="number" min="0" name="repairs[{{ $i }}][biaya]" value="{{ $f('biaya') }}"></label>
        <label class="f"><span>Keterangan</span><input name="repairs[{{ $i }}][keterangan]" value="{{ $f('keterangan') }}"></label>
    </div>
    <button type="button" class="link danger" data-remove style="margin-top:8px">Hapus baris</button>
</div>
