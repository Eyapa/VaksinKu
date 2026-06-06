<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKeluargaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorize based on logged-in user inside the controller
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'nik' => ['nullable', 'string', 'size:16'],
            'hubungan' => ['required', 'in:kepala_keluarga,istri,anak,orang_tua,lainnya'],
            'tanggal_lahir' => ['required', 'date', 'before_or_equal:today'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'no_kartu_vaksin' => ['nullable', 'string'],
        ];
    }
}
