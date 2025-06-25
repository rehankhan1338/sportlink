<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Profile;
use Illuminate\Support\Facades\Storage;

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
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
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

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
        ]);

        // Check if user has reached the limit of 5 profiles
        $profileCount = Profile::where('user_id', auth()->id())->count();
        if ($profileCount >= 5) {
            return back()->with('error', 'You can only create up to 5 profiles.');
        }

        Profile::create([
            'user_id' => auth()->id(),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
        ]);

        return redirect()->route('select-profile');
    }

    public function switch(Profile $profile)
    {
        // Verify the profile belongs to the authenticated user
        if ($profile->user_id !== auth()->id()) {
            abort(403);
        }

        // Store the selected profile in the session
        session(['selected_profile' => [
            'id' => $profile->id,
            'name' => $profile->full_name,
            'first_name' => $profile->first_name,
            'last_name' => $profile->last_name
        ]]);

        // Check if we need to redirect to a specific URL
        if (request()->has('redirect_to')) {
            return redirect(request()->get('redirect_to'));
        }

        return redirect()->route('select-profile')->with('success', 'Profile switched successfully.');
    }

    public function unlink(Profile $profile)
    {
        // Verify the profile belongs to the authenticated user
        if ($profile->user_id !== auth()->id()) {
            abort(403);
        }

        // Remove from session if this was the selected profile
        if (session('selected_profile.id') === $profile->id) {
            session()->forget('selected_profile');
        }

        $profile->delete();

        return redirect()->route('select-profile')->with('success', 'Profile unlinked successfully.');
    }

    public function getDetails()
    {
        $selectedProfile = session('selected_profile');
        
        if (!$selectedProfile) {
            return response()->json([
                'success' => false,
                'message' => 'No profile selected'
            ], 422);
        }

        $profile = Profile::find($selectedProfile['id']);
        
        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Profile not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'profile' => $profile
        ]);
    }

    public function updateDetails(Request $request)
    {
        $selectedProfile = session('selected_profile');
        
        if (!$selectedProfile) {
            return response()->json([
                'success' => false,
                'message' => 'No profile selected'
            ], 422);
        }

        $profile = Profile::find($selectedProfile['id']);
        
        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Profile not found'
            ], 404);
        }

        $request->validate([
            'weight' => 'required|numeric|min:0|max:500',
            'height' => 'required|numeric|min:0|max:300',
            'age' => 'required|integer|min:0|max:150',
            'gender' => 'required|string|in:Male,Female',
            'nationality' => 'required|string',
            'date_of_birth' => 'required|date',
            'phone' => 'required|string',
            'address' => 'required|string',
            'country_of_residence' => 'required|string',
            'passport_image' => 'nullable|image|max:2048',
            'notes' => 'nullable|string'
        ]);

        // Update profile fields
        $profile->weight = $request->weight;
        $profile->height = $request->height;
        $profile->age = $request->age;
        $profile->gender = $request->gender;
        $profile->nationality = $request->nationality;
        $profile->date_of_birth = $request->date_of_birth;
        $profile->phone = $request->phone;
        $profile->address = $request->address;
        $profile->country_of_residence = $request->country_of_residence;
        $profile->notes = $request->notes;

        // Handle passport image upload
        if ($request->hasFile('passport_image')) {
            // Delete old image if exists
            if ($profile->passport_image_path) {
                Storage::delete($profile->passport_image_path);
            }
            
            // Store new image
            $path = $request->file('passport_image')->store('passport-images', 'public');
            $profile->passport_image_path = $path;
        }

        $profile->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully'
        ]);
    }
}
