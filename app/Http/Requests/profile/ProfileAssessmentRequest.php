<?php

namespace App\Http\Requests\profile;

use Illuminate\Foundation\Http\FormRequest;

class ProfileAssessmentRequest extends FormRequest
{
    /**
     * Hanya user yang sudah login yang boleh membuat request ini.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Sanitasi field opsional: ubah string kosong menjadi null
     * sebelum validasi berjalan.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'english_test_type'       => $this->english_test_type       ?: null,
            'english_test_score'      => $this->english_test_score      ?: null,
            'target_country'          => $this->target_country          ?: null,
            'career_goal'             => $this->career_goal             ?: null,
            'organization_experience' => $this->organization_experience ?: null,
        ]);
    }

    /**
     * Aturan validasi utama.
     */
    public function rules(): array
    {
        return [
            // Akademik
            'major'           => ['required', 'string', 'max:100'],
            'education_level' => ['required', 'string', 'in:D3,D4,S1,S2,S3'],
            'semester'        => ['required', 'integer', 'min:1', 'max:14'],
            'gpa'             => ['required', 'numeric', 'min:0', 'max:4'],

            // Bahasa Inggris
            'english_level'      => ['required', 'string'],
            'english_test_type'  => ['nullable', 'string'],
            'english_test_score' => ['nullable', 'integer', 'max:999'],

            // Informasi tambahan
            'target_country'          => ['nullable', 'string', 'max:100'],
            'career_goal'             => ['nullable', 'string', 'max:1000'],
            'organization_experience' => ['nullable', 'string', 'max:1000'],

            'current_skills' => ['nullable', 'array'],
            'current_skills.*' => ['string', 'max:50'],

            'interests' => ['nullable', 'array'],
            'interests.*' => ['string', 'max:50'],

            'other_languages' => ['nullable', 'array'],
            'other_languages.*.lang' => ['nullable', 'string', 'max:50'],
            'other_languages.*.level' => ['nullable', 'string'],
        ];
    }

    /**
     * Validasi kondisional setelah rules standar dijalankan.
     */
    public function withValidator(\Illuminate\Validation\Validator $validator): void
    {
        $validator->after(function (\Illuminate\Validation\Validator $v) {
            // Jika test_type diisi tapi test_score kosong
            if ($this->english_test_type && ! $this->english_test_score) {
                $v->errors()->add(
                    'english_test_score',
                    'Skor tes wajib diisi jika jenis tes dipilih.'
                );
            }

            // Jika test_score diisi tapi test_type kosong
            if ($this->english_test_score && ! $this->english_test_type) {
                $v->errors()->add(
                    'english_test_type',
                    'Jenis tes wajib dipilih jika skor diisi.'
                );
            }
        });
    }

    /**
     * Pesan validasi dalam Bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'major.required'                  => 'Jurusan wajib diisi.',
            'major.max'                       => 'Jurusan maksimal 100 karakter.',

            'education_level.required'        => 'Jenjang pendidikan wajib dipilih.',
            'education_level.in'              => 'Jenjang pendidikan tidak valid.',

            'semester.required'               => 'Semester wajib diisi.',
            'semester.integer'                => 'Semester harus berupa angka.',
            'semester.min'                    => 'Semester minimal 1.',
            'semester.max'                    => 'Semester maksimal 14.',

            'gpa.required'                    => 'IPK wajib diisi.',
            'gpa.numeric'                     => 'IPK harus berupa angka.',
            'gpa.min'                         => 'IPK tidak boleh kurang dari 0.',
            'gpa.max'                         => 'IPK tidak boleh lebih dari 4.',

            'english_level.required'          => 'Tingkat bahasa Inggris wajib dipilih.',

            'english_test_score.integer'      => 'Skor tes harus berupa angka.',
            'english_test_score.max'          => 'Skor tes terlalu besar.',

            'career_goal.max'                 => 'Tujuan karier maksimal 1000 karakter.',
            'organization_experience.max'     => 'Pengalaman organisasi maksimal 1000 karakter.',
        ];
    }
}