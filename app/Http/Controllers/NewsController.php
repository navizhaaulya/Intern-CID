<?php

namespace App\Http\Controllers;

use App\Models\News;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use App\Models\NewsCategories;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class NewsController extends Controller
{
    /**
     * Menampilkan daftar berita dengan filter, pencarian, dan pagination.
     */
    public function index(Request $request): JsonResponse
    {
        $search      = $request->query('search');
        $limit       = $request->query('limit', 10);
        $sortBy      = $request->query('sort_by', 'id');
        $sort        = $request->query('sort', 'desc');
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
                'updater:id,fullname',
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
                    'id'                  => $item->id,
                    'category_id'         => $item->category_id,
                    'category_id_name'    => $item->category?->name,
                    'slug'                => $item->slug,
                    'title'               => $item->title,
                    'content'             => $item->content,
                    'img_cover'           => $item->img_cover,
                    'status'              => $item->status,
                    'is_highlight'        => $item->is_highlight,
                    'created_by_fullname' => $item->creator?->fullname,
                    'created_at'          => $item->created_at?->format('Y-m-d H:i:s'),
                    'updated_by'          => $item->updated_by,
                    'updated_by_fullname' => $item->updater?->fullname,
                    'updated_at'          => $item->updated_at?->format('Y-m-d H:i:s'),
                ];
            })->items(),
        ]);
    }

//    show
public function show(int $id): JsonResponse
{
    try {
        $news = News::with([
                'category:id,name',
                'creator:id,fullname',
                'updater:id,fullname',
            ])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => [
                'id'                  => $news->id,
                'slug'                => $news->slug,
                'title'               => $news->title,
                'category_id'         => $news->category_id,
                'category_id_name'    => $news->category?->name,
                'content'             => $news->content,
                'img_cover'           => $news->img_cover,
                'status'              => $news->status,
                'is_highlight'        => $news->is_highlight,
                'created_by_fullname' => $news->creator?->fullname,
                'created_at'          => $news->created_at?->format('Y-m-d H:i:s'),
                'updated_by_fullname' => $news->updater?->fullname,
                'updated_at'          => $news->updated_at?->format('Y-m-d H:i:s'),
            ],
        ]);

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Berita tidak ditemukan.',
        ], 404);
    }
}

    // create
    public function create(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'category_id' => [
                    'required',
                    'integer',
                    Rule::exists('news_categories', 'id')->where('active', true),
                ],
                'title'       => ['required', 'string', 'min:10'],
                'content'     => ['required', 'string'],
                'status'      => ['nullable', 'in:draft,publish'],
            ], [
                'category_id.required' => 'Kategori berita wajib diisi.',
                'category_id.exists'   => 'Kategori yang dipilih tidak ditemukan atau tidak aktif.',
                'title.required'       => 'Judul berita wajib diisi.',
                'title.min'            => 'Judul berita minimal 10 karakter.',
                'content.required'     => 'Konten berita wajib diisi.',
                'status.in'            => 'Status hanya boleh draft atau publish.',
            ]);

            $news = News::create([
                'category_id'  => $request->category_id,
                'slug'         => Str::slug($request->title, '-'),
                'title'        => $request->title,
                'content'      => $request->content,
                'status'       => $request->status ?? 'draft',
                'img_cover'    => null,
                'is_highlight' => false,
                'created_by' => Auth::guard('api')->id(),
                'updated_by' => Auth::guard('api')->id(),
            ]);

            $news->load([
                'category:id,name',
                'creator:id,fullname',
                'updater:id,fullname',
            ]);

            return response()->json([
                'success' => true,
                'data'    => [
                    'id'                  => $news->id,
                    'slug'                => $news->slug,
                    'title'               => $news->title,
                    'category_id'         => $news->category_id,
                    'category_id_name'    => $news->category?->name,
                    'content'             => $news->content,
                    'img_cover'           => $news->img_cover,
                    'author'              => $news->creator?->fullname,
                    'is_highlight'        => $news->is_highlight,
                    'created_by_fullname' => $news->creator?->fullname,
                    'created_at'          => $news->created_at?->format('Y-m-d H:i:s'),
                    'updated_by_fullname' => $news->updater?->fullname,
                    'updated_at'          => $news->updated_at?->format('Y-m-d H:i:s'),
                ],
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $e->errors(),
            ], 422);

        } catch (Exception $e) {
            if (str_contains($e->getMessage(), 'news_slug_unique')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Judul berita sudah pernah digunakan. Silakan gunakan judul lain.',
                ], 409);
            }

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server.',
            ], 500);
        }
    }

    // update
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $news = News::findOrFail($id);

            // kurang validasi id data tidak ditemukan

            $request->validate([
                'category_id' => 'required|exists:news_categories,id',
                'title'       => 'required|string|min:10',
                'content'     => 'required|string',
                'status'      => 'required|in:draft,publish,arsip',
                'img_cover'   => 'nullable|string',
            ]);

            if ($request->has('title')) {
                $request->merge([
                    'slug' => Str::slug($request->title, '-'),
                ]);
            }

            $news->update([
                'category_id' => $request->category_id ?? $news->category_id,
                'slug'        => $request->slug        ?? $news->slug,
                'title'       => $request->title       ?? $news->title,
                'content'     => $request->content     ?? $news->content,
                'status'      => $request->status      ?? $news->status,
                'img_cover'   => $request->img_cover   ?? $news->img_cover,
                'updated_by'  => 1,
            ]);

            $news->load([
                'category:id,name',
                'creator:id,fullname',
                'updater:id,fullname',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Berita berhasil diperbarui.',
                'data'    => [
                    'id'                  => $news->id,
                    'slug'                => $news->slug,
                    'title'               => $news->title,
                    'category_id'         => $news->category_id,
                    'category_id_name'    => $news->category?->name,
                    'content'             => $news->content,
                    'img_cover'           => $news->img_cover,
                    'status'              => $news->status,
                    'is_highlight'        => $news->is_highlight,
                    'updated_by_fullname' => $news->updater?->fullname,
                    'updated_at'          => $news->updated_at?->format('Y-m-d H:i:s'),
                ],
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $e->errors(),
            ], 422);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // delete, validated nyari id data
    public function delete(int $id): JsonResponse
{
    try { 
        $news = News::findOrFail($id);
        $title = $news->title;
        $news->delete();

        return response()->json([
            'success' => true,
            'message' => "Berita '{$title}' berhasil dihapus.",
        ]);

    } catch (ModelNotFoundException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Berita tidak ada atau sudah dihapus.',
        ], 404);

    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 500);
    }
}
    // response

    public function updateHighlight(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'id' => 'required|exists:news,id',
            ]);

            $news = News::findOrFail($request->id);

            if ($news->status !== 'publish') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya berita dengan status publish yang dapat dijadikan highlight.',
                ], 422);
            }

            News::where('is_highlight', true)->update(['is_highlight' => false]);

            // Set berita ini menjadi highlight
            $news->update([
                'is_highlight' => true,
                'updated_by'   => 1,
            ]);

            $news->load([
                'category:id,name',
                'creator:id,fullname',
                'updater:id,fullname',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Highlight berhasil diperbarui.',
                'data'    => [
                    'id'           => $news->id,
                    'title'        => $news->title, 
                    'is_highlight' => $news->is_highlight,
                    'status'       => $news->status,
                ],
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $e->errors(),
            ], 422);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}