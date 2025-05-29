<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get current stats
        $avgRating = User::whereNotNull('rating')->avg('rating');
        $totalRatings = User::whereNotNull('rating')->count();
        
        return inertia('Dashboard/Settings', [
            'user' => $user,
            'stats' => [
                'average_rating' => $avgRating ? (float) $avgRating : 0.0,
                'total_ratings' => (int) $totalRatings
            ]
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
        ]);

        $user->update($validated);

        return redirect()->route('settings')->with('success', 'Profil mis à jour avec succès');
    }

    public function uploadPhoto(Request $request)
    {
        try {
            $request->validate([
                'photo' => ['required', 'image', 'mimes:jpeg,png', 'max:1024']
            ]);

            $user = Auth::user();

            $this->storeProfilePhoto($user, $request->file('photo'));

            $user->save();

            return back()->with('success', 'Photo de profil mise à jour avec succès');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du téléchargement de la photo: '.$e->getMessage());
        }
    }

    protected function storeProfilePhoto($user, $photo)
    {
        if ($user->image) {
            Storage::disk('public')->delete($user->image);
        }

        $filename = 'user_'.$user->id.'_'.time().'.'.$photo->getClientOriginalExtension();
        $path = 'profile_images/'.$filename;

        try {
            $manager = ImageManager::withDriver('gd');
            $image = $manager->read($photo->getRealPath());
            $image->cover(200, 200);
           
            Storage::disk('public')->put($path, $image->toPng()->toString());
            $user->image = $path;

        } catch (\Exception $e) {
            \Log::error('Image processing error: ' . $e->getMessage());
            Storage::disk('public')->putFileAs('profile_images', $photo, $filename);
            $user->image = $path;
        }
    }

    public function destroyPhoto()
    {
        try {
            $user = Auth::user();

            if ($user->image) {
                Storage::disk('public')->delete($user->image);
                $user->image = null;
                $user->save();
            }

            return back()->with('success', 'Photo de profil supprimée avec succès');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression de la photo: '.$e->getMessage());
        }
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Mot de passe mis à jour avec succès');
    }

    public function updateRating(Request $request)
    {
        try {
            $request->validate([
                'rating' => ['required', 'numeric', 'min:1', 'max:5']
            ]);

            $user = Auth::user();
            $user->rating = $request->rating;
            $user->save();

            // Recalculate stats after update
            $avgRating = User::whereNotNull('rating')->avg('rating');
            $totalRatings = User::whereNotNull('rating')->count();

            return response()->json([
                'success' => true,
                'message' => 'Évaluation mise à jour avec succès',
                'average_rating' => $avgRating ? (float) $avgRating : 0.0,
                'total_ratings' => (int) $totalRatings,
                'user_rating' => (int) $request->rating
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de l\'évaluation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroyAccount(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        try {
            $user = $request->user();

            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }

            Auth::logout();

            $user->delete();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('success', 'Votre compte a été supprimé avec succès');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression du compte: '.$e->getMessage());
        }
    }
}