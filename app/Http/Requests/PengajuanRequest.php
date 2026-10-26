<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

// Dipakai untuk membuat dan memperbaiki pengajuan. Izin dicek di controller.
class PengajuanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items'                => ['nullable', 'array'],
            'items.*.nama_barang'  => ['required', 'string', 'max:255'],
            'items.*.spesifikasi'  => ['nullable', 'string', 'max:2000'],
            'items.*.link'         => ['nullable', 'url:http,https', 'max:500'],
            'items.*.biaya'        => ['required', 'integer', 'min:0'],
            'items.*.jumlah'       => ['required', 'integer', 'min:1'],
            'items.*.keterangan'   => ['nullable', 'string', 'max:255'],
            'repairs'              => ['nullable', 'array'],
            'repairs.*.nama_sarana' => ['required', 'string', 'max:255'],
            'repairs.*.kerusakan'  => ['required', 'string', 'max:2000'],
            'repairs.*.lokasi'     => ['nullable', 'string', 'max:255'],
            'repairs.*.biaya'      => ['required', 'integer', 'min:0'],
            'repairs.*.keterangan' => ['nullable', 'string', 'max:255'],
            'lampiran'             => ['nullable', 'array', 'max:5'],
            'lampiran.*'           => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'hapus_lampiran'       => ['nullable', 'array'],
            'hapus_lampiran.*'     => ['integer'],
        ];
    }

    public function after(): array
    {
        return [function (Validator $validator) {
            $items = (array) $this->input('items', []);
            $repairs = (array) $this->input('repairs', []);

            if (count($items) + count($repairs) === 0) {
                $validator->errors()->add('umum', 'Isi minimal satu barang (bagian A) atau satu perbaikan (bagian B).');
            }

            // Sesuai formulir manual: spesifikasi barang atau link marketplace wajib ada
            $no = 0;
            foreach ($items as $i => $item) {
                $no++;
                if (is_array($item) && blank($item['spesifikasi'] ?? null) && blank($item['link'] ?? null)) {
                    $validator->errors()->add("items.$i.spesifikasi", "Barang ke-$no: isi spesifikasi atau link marketplace.");
                }
            }
        }];
    }

    public function attributes(): array
    {
        return [
            'items.*.nama_barang'   => 'nama barang',
            'items.*.spesifikasi'   => 'spesifikasi',
            'items.*.link'          => 'link marketplace',
            'items.*.biaya'         => 'estimasi biaya barang',
            'items.*.jumlah'        => 'jumlah barang',
            'items.*.keterangan'    => 'keterangan barang',
            'repairs.*.nama_sarana' => 'nama sarana/prasarana',
            'repairs.*.kerusakan'   => 'kerusakan',
            'repairs.*.lokasi'      => 'lokasi',
            'repairs.*.biaya'       => 'estimasi biaya perbaikan',
            'repairs.*.keterangan'  => 'keterangan perbaikan',
            'lampiran'              => 'lampiran',
            'lampiran.*'            => 'lampiran',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Kolom :attribute wajib diisi.',
            'integer'  => 'Kolom :attribute harus berupa angka bulat.',
            'min'      => 'Kolom :attribute minimal :min.',
            'max'      => 'Kolom :attribute terlalu panjang atau terlalu besar (maksimal :max).',
            'url'      => 'Kolom :attribute harus berupa alamat web yang lengkap (diawali https://).',
            'mimes'    => 'Lampiran harus berformat PDF, JPG, atau PNG.',
            'file'     => 'Lampiran harus berupa file.',
        ];
    }
}
