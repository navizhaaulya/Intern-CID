<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voting;

class VotingController extends Controller
{
    // LIST
    public function index()
    {
        $votings = Voting::with('creator:id,fullname')
            ->where('status_code', true)
            ->orderByDesc('id')
            ->get()
            ->map(function ($voting) {
                return [
                    'id' => $voting->id,
                    'slug' => $voting->slug,
                    'img_cover' => $voting->img_cover,
                    'title' => $voting->title,
                    'description' => $voting->description,
                    'start_date' => $voting->start_date,
                    'end_date' => $voting->end_date,
                    'is_highlight' => $voting->is_highlight,
                    'created_by' => $voting->creator?->fullname,
                    'created_at' => $voting->created_at
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $votings
        ]);
    }

    // CREATE
    public function store(Request $request)
    {
        $request->validate([
            'slug' => 'required|string|unique:votings,slug',
            'title' => 'required|string',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date'
        ]);

        $voting = Voting::create([
            'slug' => $request->input('slug'),
            'img_cover' => $request->input('img_cover'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'status_code' => true,
            'is_highlight' => $request->input('is_highlight', false),
            'created_by' => 1,
            'updated_by' => 1
        ]);

        return response()->json([
            'success' => true,
            'data' => $voting
        ]);
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $voting = Voting::findOrFail($id);

        $voting->update([
            'slug' => $request->input('slug'),
            'img_cover' => $request->input('img_cover'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'is_highlight' => $request->input('is_highlight'),
            'updated_by' => 1
        ]);

        return response()->json([
            'success' => true,
            'data' => $voting
        ]);
    }

    // DELETE (soft delete)
    public function delete($id)
    {
        $voting = Voting::findOrFail($id);

        $voting->update([
            'status_code' => false,
            'updated_by' => 1
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Voting deleted'
        ]);
    }
}