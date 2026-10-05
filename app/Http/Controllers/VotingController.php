<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voting;
use App\Models\VotingLogs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;



class VotingController extends Controller
{
    // LIST (admin, semua status)
    public function index()
    {
        $votings = Voting::with('creator:id,fullname')
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
                    'status_code' => $voting->status_code,
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

    // SHOW (satu data, buat edit / detail)
    public function show($id)
    {
        $voting = Voting::with('creator:id,fullname')->find($id);

        if (!$voting) {
            return response()->json([
                'success' => false,
                'message' => 'Voting tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $voting
        ]);
    }

    // CREATE
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'status_code' => 'nullable|boolean',
        ]);

        // generate slug otomatis dari title, pastikan unik
        $slug = Str::slug($request->input('title'));
        $originalSlug = $slug;
        $i = 1;
        while (Voting::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $i;
            $i++;
        }

       $voting = Voting::create([
    'slug' => $slug,
    'img_cover' => $this->moveImageFromTmp($request->input('img_cover')),
    'title' => $request->input('title'),
            'description' => $request->input('description'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'status_code' => $request->input('status_code', true),
            'is_highlight' => false,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id()
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
    'img_cover' => $this->moveImageFromTmp($request->input('img_cover')),
    'title' => $request->input('title'),
            'description' => $request->input('description'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'status_code' => $request->input('status_code'),
            'updated_by' => Auth::id()
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
            'updated_by' => Auth::id()
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

            Voting::where('is_highlight', true)->update(['is_highlight' => false]);

            $voting->update([
                'is_highlight' => true,
                'updated_by' => Auth::id()
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

        $voting = Voting::where('id', $id)->where('status_code', true)->first();

        if (!$voting) {
            return response()->json([
                'success' => false,
                'message' => 'Voting tidak ditemukan.'
            ], 404);
        }

        $alreadyVote = VotingLogs::where('voting_id', $id)->where('user_id', $user->id)->exists();

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
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Vote berhasil disimpan.',
            'data' => $vote
        ]);
    }

    // VOTING USER (yang sudah vote)
    public function voters($id)
    {
        $voting = Voting::find($id);

        if (!$voting) {
            return response()->json([
                'success' => false,
                'message' => 'Voting tidak ditemukan.'
            ], 404);
        }

        $voters = VotingLogs::with(['user:id,fullname', 'candidate:id,title'])
            ->where('voting_id', $id)
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'user_name' => $log->user?->fullname,
                    'candidate_title' => $log->candidate?->title,
                    'voted_at' => $log->created_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $voters
        ]);
    }

    private function moveImageFromTmp($tmpPath, $folder = 'votings')
{
    if (!$tmpPath || !str_starts_with($tmpPath, 'tmp/')) {
        return $tmpPath; // udah permanent atau kosong, biarin apa adanya
    }

    if (!Storage::exists($tmpPath)) {
        return $tmpPath; // file tmp gak ketemu, biarin (biar gak fatal error)
    }

    $filename = basename($tmpPath);
    $newPath = date('Y') . '/' . date('Ym') . '/' . $folder . '/' . time() . '_' . $filename;

    Storage::move($tmpPath, $newPath);

    return $newPath;
}
}