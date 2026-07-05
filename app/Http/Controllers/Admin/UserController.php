<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * GET /admin/users
     *
     * List semua user dengan search & filter role/status.
     * Filter status='deleted' menampilkan user yang sudah soft-deleted.
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->input('status');

        $query = User::query();

        if ($statusFilter === 'deleted') {
            $query->onlyTrashed();
        }

        $users = $query
            ->search($request->input('search'))
            ->when($request->filled('role'), function ($query) use ($request) {
                $query->where('role', $request->input('role'));
            })
            ->when($statusFilter === 'active', fn ($query) => $query->active())
            ->when($statusFilter === 'suspended', fn ($query) => $query->suspended())
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'filters' => $request->only(['search', 'role', 'status']),
        ]);
    }

    /**
     * PATCH /admin/users/{user}/suspend
     */
    public function suspend(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'Anda tidak bisa menangguhkan akun Anda sendiri.');
        }

        $user->suspend();

        return back()->with('success', "User {$user->name} berhasil ditangguhkan.");
    }

    /**
     * PATCH /admin/users/{user}/activate
     */
    public function activate(User $user): RedirectResponse
    {
        $user->activate();

        return back()->with('success', "User {$user->name} berhasil diaktifkan kembali.");
    }

    /**
     * PATCH /admin/users/{user}/role
     */
    public function updateRole(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'Anda tidak bisa mengubah role akun Anda sendiri.');
        }

        $validated = $request->validate([
            'role' => 'required|in:user,admin',
        ]);

        $user->update(['role' => $validated['role']]);

        return back()->with('success', "Role {$user->name} berhasil diubah menjadi {$validated['role']}.");
    }

    /**
     * PATCH /admin/users/{user}/credentials
     *
     * Update email dan/atau password user oleh admin.
     * Password nullable — kosongkan di form berarti tidak diubah.
     *
     * Self-edit diblokir: admin harus pakai halaman Profile sendiri
     * (route('profile.edit')) untuk ubah kredensial akun miliknya,
     * supaya tidak ada dua jalur berbeda untuk hal yang sama.
     */
    public function updateCredentials(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'Gunakan halaman Profil Anda sendiri untuk mengubah email/password akun ini.');
        }

        $validated = $request->validate([
            'email' => [
                'required', 'email', 'max:150',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        $user->email = $validated['email'];

        $passwordChanged = ! empty($validated['password']);

        if ($passwordChanged) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        $message = $passwordChanged
            ? "Email dan password {$user->name} berhasil diperbarui."
            : "Email {$user->name} berhasil diperbarui.";

        return back()->with('success', $message);
    }

    /**
     * DELETE /admin/users/{user}
     *
     * Soft delete.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        $user->delete();

        return back()->with('success', "User {$user->name} berhasil dihapus.");
    }

    /**
     * PATCH /admin/users/{user}/restore
     *
     * Restore user yang sudah soft-deleted.
     * Route menggunakan ->withTrashed() supaya route model binding
     * bisa menemukan user yang sudah dihapus.
     */
    public function restore(User $user): RedirectResponse
    {
        $user->restore();

        return back()->with('success', "User {$user->name} berhasil direstore.");
    }
}