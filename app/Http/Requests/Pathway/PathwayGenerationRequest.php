<?php

namespace App\Http\Requests\Pathway;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Authorization & validation untuk POST /pathway/generate.
 *
 * Phase 3A boundary:
 * - Cek user authenticated
 * - Cek profile complete
 * - Cek target active
 *
 * Phase 3B akan tambah rate limit check di authorize().
 */
class PathwayGenerationRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        if (! $user) {
            return false;
        }

        if (! $user->profile || ! $user->profile->isComplete()) {
            return false;
        }

        if (! $user->userTarget || ! $user->userTarget->target) {
            return false;
        }

        return true;
    }

    public function rules(): array
    {
        // Phase 3A: no input field required from user
        return [];
    }

    /**
     * Custom message saat authorize() return false.
     */
    protected function failedAuthorization(): void
    {
        $user = $this->user();

        if (! $user) {
            abort(response()->json([
                'success' => false,
                'error_type' => 'unauthenticated',
                'message' => 'Anda harus login untuk generate pathway.',
            ], 401));
        }

        if (! $user->profile || ! $user->profile->isComplete()) {
            abort(response()->json([
                'success' => false,
                'error_type' => 'profile_incomplete',
                'message' => 'Lengkapi Profile Assessment terlebih dahulu sebelum generate pathway.',
                'redirect' => route('user.profile-assessment.edit'),
            ], 422));
        }

        if (! $user->userTarget || ! $user->userTarget->target) {
            abort(response()->json([
                'success' => false,
                'error_type' => 'no_target',
                'message' => 'Pilih target studi terlebih dahulu sebelum generate pathway.',
                'redirect' => route('user.targets.index'),
            ], 422));
        }

        abort(response()->json([
            'success' => false,
            'error_type' => 'forbidden',
            'message' => 'Akses ditolak.',
        ], 403));
    }
}