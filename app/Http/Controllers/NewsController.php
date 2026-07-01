<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Exception;
use App\Models\News;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $search      = $request->query('search');
        $limit       = $request->query('limit', 10);
        $sortBy      = $request->query('sort_by', 'id');
        $sort        = strtolower($request->query('sort', 'desc'));
        $categoryId  = $request->query('category_id');
        $status      = $request->query('status');
        $isHighlight = $request->query('is_highlight');

    
        $allowedSorts = ['id', 'title', 'status'];

        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }

        if (!in_array($sort, ['asc', 'desc'])) {
            $sort = 'desc';
        }

        $news = News::with([
                'category:id,name',
                'creator:id,fullname',
                'updater:id,fullname'
            ])

            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'ILIKE', "%{$search}%")
                      ->orWhere('content', 'ILIKE', "%{$search}%")
                      ->orWhereHas('category', function ($category) use ($search) {
                          $category->where('name', 'ILIKE', "%{$search}%");
                      });
                });
            })

            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })

            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })

            ->when($request->has('is_highlight'), function ($query) use ($isHighlight) {
                $query->where(
                    'is_highlight',
                    filter_var($isHighlight, FILTER_VALIDATE_BOOLEAN)
                );
            })

            ->orderBy($sortBy, $sort)

            ->paginate($limit);

        return response()->json([
            'success'   => true,
            'total'     => $news->total(),
            'totalPage' => $news->lastPage(),
            'data'      => $news->through(function ($item) {
                return [
                    'id'           => $item->id,
                    'title'        => $item->title,
                    'category'     => $item->category?->name,
                    'author'       => $item->creator?->fullname,
                    'publish_date' => optional($item->created_at)->format('Y-m-d'),
                ];
            })->items(),
        ]);
    }

    public function updateHighlight(Request $request)
{
    try {

        $request->validate([
            'id' => 'required|exists:news,id',
        ]);

        $news = News::findOrFail($request->id);

        // Hanya berita publish yang boleh di-highlight
        if ($news->status !== 'publish') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya berita dengan status publish yang dapat dijadikan highlight.'
            ], 422);
        }

        // Reset semua highlight
        News::where('is_highlight', true)
            ->update([
                'is_highlight' => false
            ]);

        // Set berita baru menjadi highlight
        $news->update([
            'is_highlight' => true,
            'updated_by' => 1,
        ]);

        $news->load([
            'category:id,name',
            'creator:id,fullname',
            'updater:id,fullname',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Highlight berhasil diperbarui.',
            'data' => [
                'id' => $news->id,
                'title' => $news->title,
                'is_highlight' => $news->is_highlight,
                'status' => $news->status,
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
    public function create(Request $request)
{
    try {

        $request->validate([
            'category_id' => 'required|exists:news_categories,id',
            'title'       => 'required|string|min:10',
            'content'     => 'required|string',
            'status'      => 'nullable|in:draft,publish',
        ]);

        $news = News::create([
            'category_id'  => $request->category_id,
            'slug'         => Str::slug($request->title) . '-' . time(),
            'title'        => $request->title,
            'content'      => $request->content,
            'status'       => $request->status ?? 'draft',
            'img_cover'    => null,
            'is_highlight' => false,
            'created_by'   => 1,
            'updated_by'   => 1,
        ]);

        // Reload relasi
        $news->load([
            'category:id,name',
            'creator:id,fullname',
            'updater:id,fullname',
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $news->id,
                'slug' => $news->slug,
                'title' => $news->title,
                'category_id' => $news->category_id,
                'category_id_name' => $news->category?->name,
                'content' => $news->content,
                'img_cover' => $news->img_cover,
                'author' => $news->creator?->fullname,
                'is_highlight' => $news->is_highlight,
                'created_by_fullname' => $news->creator?->fullname,
                'created_at' => $news->created_at?->format('Y-m-d H:i:s'),
                'updated_by_fullname' => $news->updater?->fullname,
                'updated_at' => $news->updated_at?->format('Y-m-d H:i:s'),
            ]
        ], 201);

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
}