<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GameMatch;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class AdminMatchController extends Controller
{
    public function index()
    {
        try {
            $matches = GameMatch::with([
                'player1' => function($query) {
                    $query->select('id', 'first_name', 'last_name', 'nationality', 'passport_image_path', 'user_id')
                        ->with(['user:id,email']);
                },
                'player2' => function($query) {
                    $query->select('id', 'first_name', 'last_name', 'nationality', 'passport_image_path', 'user_id')
                        ->with(['user:id,email']);
                },
                'division:id,name,gender,min_age,max_age,min_weight,max_weight,belt_level'
            ])
            ->orderBy('scheduled_time', 'desc')
            ->get()
            ->map(function ($match) {
                // Format player1 image path
                if ($match->player1 && $match->player1->passport_image_path) {
                    // Check if the path already starts with storage URL
                    if (!str_starts_with($match->player1->passport_image_path, 'http://') && !str_starts_with($match->player1->passport_image_path, 'https://')) {
                        $match->player1->passport_image_path = asset('storage/' . $match->player1->passport_image_path);
                    }
                }

                // Format player2 image path
                if ($match->player2 && $match->player2->passport_image_path) {
                    // Check if the path already starts with storage URL
                    if (!str_starts_with($match->player2->passport_image_path, 'http://') && !str_starts_with($match->player2->passport_image_path, 'https://')) {
                        $match->player2->passport_image_path = asset('storage/' . $match->player2->passport_image_path);
                    }
                }

                return $match;
            });

            return Inertia::render('Admin/Matches/Index', [
                'matches' => $matches
            ]);
        } catch (\Exception $e) {
            return Inertia::render('Admin/Matches/Index', [
                'matches' => [],
                'error' => 'Failed to load matches. ' . $e->getMessage()
            ]);
        }
    }

    public function edit($id)
    {
        try {
            $match = GameMatch::with([
                'player1' => function($query) {
                    $query->select('id', 'first_name', 'last_name', 'nationality', 'passport_image_path', 'user_id')
                        ->with(['user:id,email']);
                },
                'player2' => function($query) {
                    $query->select('id', 'first_name', 'last_name', 'nationality', 'passport_image_path', 'user_id')
                        ->with(['user:id,email']);
                },
                'division:id,name,gender,min_age,max_age,min_weight,max_weight,belt_level'
            ])->findOrFail($id);

            // Format image paths
            if ($match->player1 && $match->player1->passport_image_path) {
                if (!str_starts_with($match->player1->passport_image_path, 'http://') && !str_starts_with($match->player1->passport_image_path, 'https://')) {
                    $match->player1->passport_image_path = asset('storage/' . $match->player1->passport_image_path);
                }
            }

            if ($match->player2 && $match->player2->passport_image_path) {
                if (!str_starts_with($match->player2->passport_image_path, 'http://') && !str_starts_with($match->player2->passport_image_path, 'https://')) {
                    $match->player2->passport_image_path = asset('storage/' . $match->player2->passport_image_path);
                }
            }

            return Inertia::render('Admin/Matches/Edit', [
                'match' => $match
            ]);
        } catch (\Exception $e) {
            return redirect()->route('admin.matches.index')->with('error', 'Failed to load match. ' . $e->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        try {
            $match = GameMatch::findOrFail($id);
            
            $request->validate([
                'who_won' => 'required|exists:profiles,id'
            ]);

            $match->update([
                'who_won' => $request->who_won,
                'status' => 'completed'
            ]);

            return redirect()->route('admin.matches.index')->with('success', 'Match updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update match. ' . $e->getMessage());
        }
    }
} 