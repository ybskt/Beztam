<?php

namespace App\Http\Controllers;

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
        return inertia('Dashboard/Settings', [
            'user' => Auth::user(),
            'flash' => session()->get('flash', [])
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

        return redirect()->route('settings')->with('flash', [
            'success' => 'Profile updated successfully.'
        ]);
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

            return back()->with('flash', [
                'success' => 'Profile photo updated successfully.'
            ]);

        } catch (\Exception $e) {
            return back()->with('flash', [
                'error' => 'Error uploading photo: '.$e->getMessage()
            ]);
        }
    }

    protected function storeProfilePhoto($user, $photo)
    {
        // Delete old photo if exists
        if ($user->image) {
            Storage::disk('public')->delete($user->image);
        }

        // Generate filename
        $filename = 'user_'.$user->id.'_'.time().'.'.$photo->getClientOriginalExtension();
        $path = 'profile_images/'.$filename;

        try {
            // For Intervention Image 3.x
            $manager = ImageManager::withDriver('gd');
            $image = $manager->read($photo->getRealPath());
            $image->cover(200, 200);
            
            // Store image in public/profile_images
            Storage::disk('public')->put($path, $image->toPng()->toString());

            // Update user with relative path
            $user->image = $path;
        } catch (\Exception $e) {
            // Log the specific error
            \Log::error('Image processing error: ' . $e->getMessage());
            
            // Fallback to basic file storage if image processing fails
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

            return back()->with('flash', [
                'success' => 'Profile photo removed.'
            ]);

        } catch (\Exception $e) {
            return back()->with('flash', [
                'error' => 'Error removing photo: '.$e->getMessage()
            ]);
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

        return back()->with('flash', [
            'success' => 'Password updated successfully.'
        ]);
    }

    public function destroyAccount(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);
    
        try {
            $user = $request->user();
    
            // Delete profile photo if exists
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }
    
            // Logout before deletion to prevent session issues
            Auth::logout();
    
            // Delete the user
            $user->delete();
    
            // Invalidate session
            $request->session()->invalidate();
            $request->session()->regenerateToken();
    
            // Redirect to login page with success message
            return redirect()->route('login')->with('flash', [
                'success' => 'Your account has been permanently deleted.'
            ]);
    
        } catch (\Exception $e) {
            return back()->with('flash', [
                'error' => 'Error deleting account: '.$e->getMessage()
            ]);
        }
    }

    
}