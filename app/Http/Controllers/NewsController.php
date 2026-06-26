<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;

class NewsController extends Controller
{
 // LIST
public function index(Request $request)
{
    $query = News::with([
        'category:id,name',
        'creator:id,fullname',
        'updater:id,fullname'
    ]);

    // SEARCH
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('title', 'ILIKE', "%{$search}%")
              ->orWhere('content', 'ILIKE', "%{$search}%");
        });
    }

    // FILTER CATEGORY
    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
    }

    // FILTER STATUS
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    } else {
        // Default hanya tampilkan publish
        $query->where('status', 'publish');
    }

    // FILTER HIGHLIGHT
    if ($request->has('is_highlight')) {
        $query->where(
            'is_highlight',
            filter_var($request->is_highlight, FILTER_VALIDATE_BOOLEAN)
        );
    }

    // SORTING
    $sortBy = $request->get('sort_by', 'id');
    $sort = strtolower($request->get('sort', 'desc'));

    $allowedSortBy = ['id', 'title', 'status'];

    if (!in_array($sortBy, $allowedSortBy)) {
        $sortBy = 'id';
    }

    if (!in_array($sort, ['asc', 'desc'])) {
        $sort = 'desc';
    }

    $query->orderBy($sortBy, $sort);

    // PAGINATION
    $limit = (int) $request->get('limit', 10);
    $page = (int) $request->get('page', 1);

    $news = $query
        ->paginate($limit, ['*'], 'page', $page)
        ->through(function ($item) {
            return [
                'id' => $item->id,
                'slug' => $item->slug,
                'title' => $item->title,
                'category_id' => $item->category_id,
                'category_name' => $item->category?->name,
                'content' => $item->content,
                'img_cover' => $item->img_cover,
                'status' => $item->status,
                'is_highlight' => $item->is_highlight,
                'created_by_fullname' => $item->creator?->fullname,
                'updated_by_fullname' => $item->updater?->fullname,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ];
        });

    return response()->json([
        'success' => true,
        'data' => $news
    ]);
}
    // CREATE
    public function store(Request $request)
    {
        $request->validate([
            'slug' => 'required|string|unique:news,slug',
            'title' => 'required|string',
            'content' => 'required|string'
        ]);

        $news = News::create([
            'slug' => $request->input('slug'),
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'img_cover' => $request->input('img_cover'),
            'status' => $request->input('status', 'draft'),
            'is_highlight' => $request->input('is_highlight', false),
            'created_by' => 1,
            'updated_by' => 1
        ]);

        return response()->json([
            'success' => true,
            'data' => $news
        ]);
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);

        $news->update([
            'slug' => $request->input('slug'),
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'img_cover' => $request->input('img_cover'),
            'status' => $request->input('status'),
            'is_highlight' => $request->input('is_highlight'),
            'updated_by' => 1
        ]);

        return response()->json([
            'success' => true,
            'data' => $news
        ]);
    }

    // DELETE
    public function delete($id)
    {
        $news = News::findOrFail($id);

        $news->delete();

        return response()->json([
            'success' => true,
            'message' => 'News deleted'
        ]);
    }
}