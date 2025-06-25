<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Academy;
use App\Models\Affiliation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AcademyController extends Controller
{
    public function index()
    {
        $academies = Academy::with(['affiliation'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('Admin/Academies/Index', [
            'academies' => $academies
        ]);
    }

    public function create()
    {
        $affiliations = Affiliation::select('id', 'name')->get();
        
        return Inertia::render('Admin/Academies/Create', [
            'affiliations' => $affiliations
        ]);
    }

    public function store(Request $request)
    {
        try {
            // Start a database transaction
            DB::beginTransaction();

            // Validate the request
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'country' => 'required|string|max:100',
                'city' => 'required|string|max:100',
                'address' => 'required|string|max:255',
                'email' => [
                    'required',
                    'email',
                    'max:255',
                    Rule::unique('users', 'email'), // Make sure email doesn't exist in users table
                ],
                'person_in_charge' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'website' => 'nullable|url|max:255',
                'about' => 'nullable|string|max:1000',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'cover' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            // Create a new user for the academy
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt(Str::random(16)), // Generate a random password
                'email_verified_at' => now(), // Mark email as verified since it's admin-created
            ]);

            if (!$user || !$user->id) {
                throw new \Exception('Failed to create user account.');
            }

            // Set the user_id
            $validated['user_id'] = $user->id;

            // Handle file uploads
            if ($request->hasFile('logo')) {
                $logoFile = $request->file('logo');
                if ($logoFile->isValid()) {
                    $validated['logo'] = $logoFile->store('logos', 'academies');
                } else {
                    throw new \Exception('Invalid logo file upload.');
                }
            }

            if ($request->hasFile('cover')) {
                $coverFile = $request->file('cover');
                if ($coverFile->isValid()) {
                    $validated['cover_image'] = $coverFile->store('covers', 'academies');
                } else {
                    throw new \Exception('Invalid cover file upload.');
                }
            }

            // Remove the file objects as they are not database columns
            unset($validated['logo_file']);
            unset($validated['cover_file']);

            \Log::info('Creating academy with data:', array_merge(
                $validated,
                ['user_id' => $user->id]
            ));

            // Create the academy
            $academy = Academy::create($validated);

            if (!$academy || !$academy->id) {
                throw new \Exception('Failed to create academy record.');
            }

            // If everything is successful, commit the transaction
            DB::commit();

            \Log::info('Academy created successfully:', [
                'academy_id' => $academy->id,
                'user_id' => $user->id
            ]);

            return redirect()->route('admin.academies.index')
                ->with('success', 'Academy created successfully.');

        } catch (\Exception $e) {
            // If anything fails, rollback the transaction
            DB::rollBack();

            \Log::error('Error creating academy:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Delete any uploaded files if they exist
            if (isset($validated['logo'])) {
                Storage::disk('academies')->delete($validated['logo']);
            }
            if (isset($validated['cover_image'])) {
                Storage::disk('academies')->delete($validated['cover_image']);
            }

            return back()->withInput()
                ->withErrors(['error' => 'Failed to create academy. ' . $e->getMessage()]);
        }
    }

    public function edit(Academy $academy)
    {
        $affiliations = Affiliation::select('id', 'name')->get();
        
        return Inertia::render('Admin/Academies/Edit', [
            'academy' => $academy,
            'affiliations' => $affiliations
        ]);
    }

    public function update(Request $request, Academy $academy)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'country' => 'required|string',
                'city' => 'required|string',
                'address' => 'required|string',
                'email' => 'required|email',
                'person_in_charge' => 'required|string',
                'phone' => 'required|string',
                'website' => 'nullable|url',
                'about' => 'nullable|string',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'cover' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            // Get the authenticated admin user
            $admin = auth('admin')->user();
            if (!$admin) {
                throw new \Exception('No authenticated admin user found.');
            }

            // Set the user_id to the admin's ID if it's not already set
            if (!$academy->user_id) {
                $validated['user_id'] = $admin->id;
            }

            if ($request->hasFile('logo')) {
                if ($academy->logo) {
                    Storage::disk('academies')->delete($academy->logo);
                }
                $validated['logo'] = $request->file('logo')->store('logos', 'academies');
            }

            if ($request->hasFile('cover')) {
                if ($academy->cover_image) {
                    Storage::disk('academies')->delete($academy->cover_image);
                }
                $validated['cover_image'] = $request->file('cover')->store('covers', 'academies');
            }

            // Remove the file objects as they are not database columns
            unset($validated['logo_file']);
            unset($validated['cover_file']);

            \Log::info('Updating academy with data:', [
                'academy_id' => $academy->id,
                'data' => $validated
            ]);

            $academy->update($validated);

            \Log::info('Academy updated successfully:', ['id' => $academy->id]);

            return redirect()->route('admin.academies.index')
                ->with('success', 'Academy updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Error updating academy:', [
                'academy_id' => $academy->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors(['error' => 'Failed to update academy. ' . $e->getMessage()]);
        }
    }

    public function destroy(Academy $academy)
    {
        if ($academy->logo) {
            Storage::disk('academies')->delete($academy->logo);
        }

        if ($academy->cover_image) {
            Storage::disk('academies')->delete($academy->cover_image);
        }

        $academy->delete();

        return redirect()->route('admin.academies.index')
            ->with('success', 'Academy deleted successfully.');
    }

    public function show(Academy $academy)
    {
        return Inertia::render('Admin/Academies/Show', [
            'academy' => $academy->load(['affiliation', 'members'])
        ]);
    }
} 