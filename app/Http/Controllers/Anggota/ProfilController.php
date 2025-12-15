<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Tampilkan profil anggota.
     */
    public function show()
    {
        $user = Auth::user();

        return view('anggota.profile.show', compact('user'));
    }

    /**
     * Form edit profil.
     */
    public function edit()
    {
        $user = Auth::user();

        return view('anggota.profile.edit', compact('user'));
    }

    /**
     * Update profil anggota.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => 'nullable|string|max:20',
            // Tambahkan field lain sesuai kolom di tabel users
        ]);

        $user->update($data);

        return redirect()
            ->route('anggota.profile.show')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
