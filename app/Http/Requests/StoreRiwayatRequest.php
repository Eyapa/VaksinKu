<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRiwayatRequest extends FormRequest
{
    public function authorize(): bool
    {
        $anggota = \App\Models\AnggotaKeluarga::find($this->input('anggota_keluarga_id'));
        return $anggota && $anggota->user_id === $this->user()->id;
    }

    public function rules(): array
    {
        return [
            'anggota_keluarga_id' => ['required', 'integer', 'exists:anggota_keluargas,id'],
            'vaksin_id'           => ['required', 'integer', 'exists:vaksins,id'],
            'faskes_id'           => ['nullable', 'integer', 'exists:fasilitas_kesehatans,id'],
            'nomor_dosis'         => ['required', 'integer', 'min:1'],
            'tanggal_vaksin'      => ['required', 'date', 'before_or_equal:today'],
            'nomor_batch'         => ['nullable', 'string'],
            'nama_tenaga_medis'   => ['nullable', 'string'],
            'catatan'             => ['nullable', 'string', 'max:500'],
        ];
    }
}
