<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJadwalRequest extends FormRequest
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
            'faskes_id'           => ['required', 'integer', 'exists:fasilitas_kesehatans,id'],
            'tanggal_jadwal'      => ['required', 'date', 'after:today'],
            'jam_mulai'           => ['required', 'date_format:H:i'],
            'catatan'             => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal_jadwal.after'  => 'Tanggal jadwal harus setelah hari ini.',
            'anggota_keluarga_id.exists' => 'Anggota keluarga tidak ditemukan.',
        ];
    }
}
