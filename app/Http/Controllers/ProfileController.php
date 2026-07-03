<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    
public function index(Request $request): View
    {
        return view('profile.index', [
            'user' => $request->user(),
        ]);
    }

/**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        // Mengisi data name dan email dari form
        $user->fill($request->validated());

        // Reset verifikasi jika email berubah
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Logika untuk upload dan simpan Avatar
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama dari folder jika ada (agar penyimpanan tidak penuh)
            if ($user->avatar && Storage::disk('public')->exists('avatars/' . $user->avatar)) {
                Storage::disk('public')->delete('avatars/' . $user->avatar);
            }

            // Buat nama file unik berdasarkan waktu saat ini
            $avatarName = time() . '.' . $request->avatar->extension();
            
            // Simpan gambar ke folder storage/app/public/avatars
            $request->avatar->storeAs('avatars', $avatarName, 'public');
            
            // Masukkan nama file ke dalam atribut user untuk disimpan ke database
            $user->avatar = $avatarName;
        }

        // Simpan semua perubahan ke database
        $user->save();

        return Redirect::route('profile.index')->with('status', 'Profile updated successfully!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
