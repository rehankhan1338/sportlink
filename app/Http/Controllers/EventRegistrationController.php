<?php

namespace App\Http\Controllers;

use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class EventRegistrationController extends Controller
{
    public function store(Request $request)
    {
        try {
            Log::info('Registration request received:', $request->all());

            // Validate required fields
            $validated = $request->validate([
                'event_id' => 'required|exists:events,id',
                'email' => 'required|email',
                'weight' => 'required|numeric|min:0',
                'age' => 'required|integer|min:0',
                'gender' => 'required|string|in:Male,Female',
                'nationality' => 'required|string',
                'date_of_birth' => 'required|date',
                'phone' => 'required|string|min:10',
                'address' => 'required|string|min:10',
                'country_of_residence' => 'required|string',
                'height' => 'required|numeric|min:0',
                'passport_image' => 'required|image|max:2048',
                'notes' => 'nullable|string'
            ], [
                'event_id.required' => 'Event ID is required',
                'email.required' => 'Email is required',
                'email.email' => 'Please enter a valid email address',
                'weight.required' => 'Weight is required',
                'weight.numeric' => 'Weight must be a number',
                'weight.min' => 'Weight must be greater than 0',
                'age.required' => 'Age is required',
                'age.integer' => 'Age must be a whole number',
                'age.min' => 'Age must be greater than 0',
                'gender.required' => 'Gender is required',
                'gender.in' => 'Please select a valid gender',
                'nationality.required' => 'Nationality is required',
                'date_of_birth.required' => 'Date of birth is required',
                'date_of_birth.date' => 'Please enter a valid date',
                'phone.required' => 'Phone number is required',
                'phone.min' => 'Phone number must be at least 10 characters',
                'address.required' => 'Address is required',
                'address.min' => 'Address must be at least 10 characters',
                'country_of_residence.required' => 'Country of residence is required',
                'height.required' => 'Height is required',
                'height.numeric' => 'Height must be a number',
                'height.min' => 'Height must be greater than 0',
                'passport_image.required' => 'Passport/Player ID image is required',
                'passport_image.image' => 'Please upload a valid image file',
                'passport_image.max' => 'Image size must be less than 2MB'
            ]);

            Log::info('Validation passed');

            // Handle file upload
            if ($request->hasFile('passport_image')) {
                $file = $request->file('passport_image');
                $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
                
                // Store the file in the registers directory
                $path = Storage::disk('registers')->putFileAs('', $file, $filename);
                $validated['passport_image_path'] = $filename;
                
                Log::info('File uploaded successfully:', ['path' => $path]);
            }

            // Add user_id to the validated data
            $validated['user_id'] = auth()->id();

            Log::info('Attempting to create registration with data:', $validated);

            // Create the registration
            $registration = EventRegistration::create($validated);
            
            Log::info('Registration created successfully:', [
                'id' => $registration->id,
                'event_id' => $registration->event_id,
                'user_id' => $registration->user_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Registration completed successfully!',
                'registration' => $registration
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed:', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Registration failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Registration failed. Please try again.'
            ], 500);
        }
    }
} 