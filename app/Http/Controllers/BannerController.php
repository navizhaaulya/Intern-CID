<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;

class BannerController extends Controller
{
    // LIST
    public function index()
    {
        $banners = Banner::with('creator:id,fullname')
            ->where('status_code', true)
            ->orderByDesc('id')
            ->get()
            ->map(function ($banner) {
                return [
                    'id' => $banner->id,
                    'title' => $banner->title,
                    'img_cover' => $banner->img_cover,
                    'url' => $banner->url,
                    'created_by' => $banner->creator?->fullname,
                    'created_at' => $banner->created_at
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $banners
        ]);
    }

    // CREATE
    public function store(Request $request)
    {
        $request->validate([
            'img_cover' => 'required|string'
        ]);

        $banner = Banner::create([
            'title' => $request->input('title'),
            'img_cover' => $request->input('img_cover'),
            'url' => $request->input('url'),
            'status_code' => true,
            'created_by' => 1,
            'updated_by' => 1
        ]);

        return response()->json([
            'success' => true,
            'data' => $banner
        ]);
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $banner->update([
            'title' => $request->input('title'),
            'img_cover' => $request->input('img_cover'),
            'url' => $request->input('url'),
            'updated_by' => 1
        ]);

        return response()->json([
            'success' => true,
            'data' => $banner
        ]);
    }

    // DELETE (soft delete)
    public function delete($id)
    {
        $banner = Banner::findOrFail($id);

        $banner->update([
            'status_code' => false,
            'updated_by' => 1
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Banner deleted'
        ]);
    }
}