<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GlobalConfig;
use App\Models\Mission;

class VisionMissionController extends Controller
{
    // READ
    public function index()
    {
        $vision = GlobalConfig::first();

        $missions = Mission::query()
            ->where('status_code', true)
            ->orderBy('order')
            ->get([
                'id',
                'content',
                'order'
            ]);

        return response()->json([
            'data' => [
                'mission' => $missions,
                'vision' => $vision?->school_vission
            ],
            'success' => true
        ]);
    }

    // CREATE
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'order' => 'required|integer'
        ]);

        $mission = Mission::create([
            'content' => $request->input('content'),
            'order' => $request->input('order'),
            'status_code' => true,
            'created_by' => 1
        ]);

        return response()->json([
            'success' => true,
            'data' => $mission
        ]);
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $mission = Mission::findOrFail($id);

        $mission->update([
            'content' => $request->input('content'),
            'order' => $request->input('order'),
            'updated_by' => 1
        ]);

        return response()->json([
            'success' => true,
            'data' => $mission
        ]);
    }

    // DELETE (soft delete)
    public function delete($id)
    {
        $mission = Mission::findOrFail($id);

        $mission->update([
            'status_code' => false,
            'updated_by' => 1
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Mission deleted'
        ]);
    }
}