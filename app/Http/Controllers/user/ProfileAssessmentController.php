<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\ProfileAssessmentRequest;
use App\Models\Profile;

class ProfileAssessmentController extends Controller
{
    /**
     * Tampilkan halaman ringkasan profil.
     */
    public function index()
    {
        $user    = auth()->user();
        $profile = $user->profile;

        return view('user.profile-assessment.index', compact('profile'));
    }

    /**
     * Tampilkan form buat / edit profil.
     */
    public function edit()
    {
        $user    = auth()->user();
        $profile = $user->profile;

        return view('user.profile-assessment.edit', compact('profile'));
    }

    /**
     * Simpan profil baru.
     */
    public function store(ProfileAssessmentRequest $request)
    {
        $user = auth()->user();

        // Jika profil sudah ada, arahkan ke update flow
        if ($user->profile) {
            return redirect()
                ->route('profile-assessment.edit')
                ->with('warning', 'Profil sudah ada. Silakan perbarui profil Anda.');
        }

        $data              = $request->validated();
        $data['user_id']   = auth()->id();

        Profile::create($data);

        return redirect()
            ->route('profile-assessment.show')
            ->with('success', 'Profil berhasil disimpan.');
    }

    /**
     * Perbarui profil yang sudah ada.
     */
  public function update(ProfileAssessmentRequest $request)
{
    $profile = auth()->user()->profile;

    if (! $profile) {
        return redirect()
            ->route('profile-assessment.edit')
            ->with('warning', 'Profil belum ditemukan. Silakan buat profil terlebih dahulu.');
    }

    $profile->update($request->validated());

    return redirect()
        ->route('profile-assessment.show')
        ->with('success', 'Profil berhasil diperbarui.');
}

    /**
     * Tampilkan detail profil.
     */
    public function show()
    {
        $profile = auth()->user()->profile;

        if (! $profile) {
            return redirect()->route('profile-assessment.edit');
        }

        return view('user.profile-assessment.show', compact('profile'));
    }
}