<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voting;
use App\Models\VotingLogs;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Validation\ValidationException;

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

    // UPDATE HIGHLIGHT
public function updateHighlight(Request $request)
{
    try {

        $request->validate([
            'id' => 'required|exists:votings,id',
        ]);


        $voting = Voting::findOrFail($request->id);


        if (!$voting->status_code) {
            return response()->json([
                'success' => false,
                'message' => 'Voting tidak aktif.'
            ], 422);
        }


        // reset semua highlight
        Voting::where('is_highlight', true)
            ->update([
                'is_highlight' => false
            ]);


        // set voting pilihan jadi highlight
        $voting->update([
            'is_highlight' => true,
            'updated_by' => 1
        ]);


        return response()->json([
            'success' => true,
            'message' => 'Highlight voting berhasil diperbarui.',
            'data' => [
                'id' => $voting->id,
                'title' => $voting->title,
                'is_highlight' => $voting->is_highlight
            ]
        ]);


    } catch (ValidationException $e) {

        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);


    } catch (Exception $e) {

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);

    }
}

    // VOTE
public function vote(Request $request, $id)
{
    $request->validate([
        'candidate_id' => 'required|exists:voting_candidates,id'
    ]);

    $user = Auth::guard('api')->user();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Silakan login terlebih dahulu.'
        ], 401);
    }


    $voting = Voting::where('id', $id)
        ->where('status_code', true)
        ->first();


    if (!$voting) {
        return response()->json([
            'success' => false,
            'message' => 'Voting tidak ditemukan.'
        ], 404);
    }


    // cek apakah user sudah vote
    $alreadyVote = VotingLogs::where('voting_id', $id)
        ->where('user_id', $user->id)
        ->exists();


    if ($alreadyVote) {
        return response()->json([
            'success' => false,
            'message' => 'Anda sudah memberikan suara.'
        ], 422);
    }


    $vote = VotingLogs::create([
        'voting_id' => $id,
        'candidate_id' => $request->candidate_id,
        'user_id' => $user->id,
        'created_by' => $user->id,
    ]);


    return response()->json([
        'success' => true,
        'message' => 'Vote berhasil disimpan.',
        'data' => $vote
    ]);
}
}