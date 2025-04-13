<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class SettingsController extends Controller
{
    public function index()
    {
        return inertia('Dashboard/Settings', [
            'user' => Auth::user(),
            'flash' => session()->get('flash') // Ensure flash is always available
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png', 'max:1024'],
        ]);

        $user->first_name = $validated['first_name'];
        $user->last_name = $validated['last_name'];
        $user->email = $validated['email'];

        if ($request->hasFile('photo')) {
            $this->storeProfilePhoto($user, $request->file('photo'));
        }

        $user->save();

        return redirect()->route('settings')
            ->with('flash', ['success' => 'Profile updated successfully.']);
    }

    public function destroyPhoto()
    {
        $user = Auth::user();

        if ($user->image) {
            Storage::disk('public')->delete($user->image);
            $user->image = null;
            $user->save();
        }

        return back()->with('success', 'Profile photo removed.');
    }

    protected function storeProfilePhoto($user, $photo)
    {
        if ($user->image) {
            Storage::disk('public')->delete($user->image);
        }

        $manager = new ImageManager(new Driver());
        $filename = $user->id.'.'.$photo->getClientOriginalExtension();
        $path = 'profile-photos/'.$filename;

        $image = $manager->read($photo->getRealPath());
        $image->cover(200, 200);
        $image->toPng(80);

        Storage::disk('public')->put($path, $image);
        $user->image = $path;
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password updated successfully.');
    }

    public function destroyAccount(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Delete profile photo if exists
        if ($user->image) {
            Storage::disk('public')->delete($user->image);
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}