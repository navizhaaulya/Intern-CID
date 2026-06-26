<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Major;

class MajorController extends Controller
{
    // LIST
    public function index()
    {
        $majors = Major::with('creator:id,fullname')
            ->where('status_code', true)
            ->orderBy('major_name')
            ->get()
            ->map(function ($major) {
                return [
                    'id' => $major->id,
                    'slug' => $major->slug,
                    'img_logo' => $major->img_logo,
                    'code' => $major->code,
                    'major_name' => $major->major_name,
                    'summary' => $major->summary,
                    'total_classes' => $major->total_classes,
                    'major_duration' => $major->major_duration,
                    'full_description' => $major->full_description,
                    'created_by' => $major->creator?->fullname,
                    'created_at' => $major->created_at
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $majors
        ]);
    }

    // CREATE
    public function store(Request $request)
    {
        $request->validate([
            'slug' => 'required|string|unique:majors,slug',
            'img_logo' => 'required|string',
            'code' => 'required|string',
            'major_name' => 'required|string',
            'summary' => 'required|string',
            'total_classes' => 'required|integer',
            'major_duration' => 'required|integer',
            'full_description' => 'required|string'
        ]);

        $major = Major::create([
            'slug' => $request->input('slug'),
            'img_logo' => $request->input('img_logo'),
            'code' => $request->input('code'),
            'major_name' => $request->input('major_name'),
            'summary' => $request->input('summary'),
            'total_classes' => $request->input('total_classes'),
            'major_duration' => $request->input('major_duration'),
            'full_description' => $request->input('full_description'),
            'status_code' => true,
            'created_by' => 1,
            'updated_by' => 1
        ]);

        return response()->json([
            'success' => true,
            'data' => $major
        ]);
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $major = Major::findOrFail($id);

        $major->update([
            'slug' => $request->input('slug'),
            'img_logo' => $request->input('img_logo'),
            'code' => $request->input('code'),
            'major_name' => $request->input('major_name'),
            'summary' => $request->input('summary'),
            'total_classes' => $request->input('total_classes'),
            'major_duration' => $request->input('major_duration'),
            'full_description' => $request->input('full_description'),
            'updated_by' => 1
        ]);

        return response()->json([
            'success' => true,
            'data' => $major
        ]);
    }

    // DELETE
    public function delete($id)
    {
        $major = Major::findOrFail($id);

        $major->update([
            'status_code' => false,
            'updated_by' => 1
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Major deleted'
        ]);
    }
}