<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse|JsonResponse
    {
        $user = $request->user();
        $data = $request->validated();

        try {
            DB::beginTransaction();

            // Gestion de la mise à jour de l'avatar
        if ($request->hasFile('avatar')) {
                $avatarPath = storage_path('app/public/avatars');
                if (!file_exists($avatarPath)) {
                    mkdir($avatarPath, 0755, true);
                }
            
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

                $fileName = uniqid('avatar_') . '.' . $request->file('avatar')->getClientOriginalExtension();
                $path = $request->file('avatar')->storeAs('avatars', $fileName, 'public');
            $data['avatar'] = $path;
        }

            // Ne mettre à jour que les champs qui ont été soumis
            foreach ($data as $field => $value) {
                if ($field === 'email' && $value !== $user->email) {
            $user->email_verified_at = null;
                }
                $user->{$field} = $value;
        }

        $user->save();
            DB::commit();

            return response()->json([
                'message' => 'Profil mis à jour avec succès',
                'user' => $user
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating profile: ' . $e->getMessage(), [
                'exception' => $e,
            'user_id' => $user->id,
                'data' => $data
        ]);

            return response()->json([
                'error' => 'Une erreur est survenue lors de la mise à jour du profil.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Suppression de l'avatar lors de la suppression du compte
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
