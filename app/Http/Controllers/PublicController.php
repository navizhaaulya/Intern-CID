<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Event;
use App\Models\Feedbacks;
use App\Models\FeedbackCategories;
use App\Models\GlobalConfig;
use App\Models\Major;
use App\Models\Voting;
use App\Models\VotingCandidates;
use App\Models\NewsCategories;
use App\Models\VotingLogs;
use App\Models\News;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PublicController extends Controller
{
   public function banners(): JsonResponse
{
    $banners = Banner::where('status_code', true)
        ->orderBy('id', 'desc')
        ->get(['id', 'title', 'img_cover', 'url'])
        ->map(function ($banner) {
            return [
                'id' => $banner->id,
                'title' => $banner->title,
                'img_cover' => $banner->img_cover
                    ? url('api/file/banner/img_cover/' . $banner->id . '/' . time())
                    : null,
                'url' => $banner->url,
            ];
        });

    return response()->json([
        'success' => true,
        'data'    => $banners,
    ]);
}

public function about(): JsonResponse
{
    $config = GlobalConfig::first();

    if (!$config) {
        return response()->json(['success' => false, 'message' => 'Data belum tersedia.'], 404);
    }

    $imgProfile1 = $config->img_profile_1
        ? columnValueToFileObject('img_profile_1', $config->img_profile_1, 'global_config', $config->id)
        : null;

    $imgProfile2 = $config->img_profile_2
        ? columnValueToFileObject('img_profile_2', $config->img_profile_2, 'global_config', $config->id)
        : null;

    return response()->json([
        'success' => true,
        'data'    => [
            'motto'                => $config->motto,
            'profile_title'        => $config->profile_title,
            'profile_description'  => $config->profile_description,
            'img_profile_1'        => $imgProfile1,
            'img_profile_2'        => $imgProfile2,
            'video_profile'        => $config->video_profile,
            'school_name'       => $config->school_name, 
            'headline_title'       => $config->headline_title, 
        ],
    ]);
}


public function visionMission(): JsonResponse
{
    $config = GlobalConfig::first();

    if (!$config) {
        return response()->json(['success' => false, 'message' => 'Data belum tersedia.'], 404);
    }

    $missions = collect($config->missions ?? [])->sortBy('order')->values();

    return response()->json([
        'success' => true,
        'data'    => [
            'vision'   => $config->vision,
            'missions' => $missions,
        ],
    ]);
}
public function footer(): JsonResponse
{
    $config = GlobalConfig::first();

    if (!$config) {
        return response()->json(['success' => false, 'message' => 'Data belum tersedia.'], 404);
    }

    return response()->json([
        'success' => true,
        'data'    => [
            'school_name'        => $config->school_name,
            'footer_description' => $config->footer_description,
            'school_telephone'   => $config->school_telephone,
            'school_email'       => $config->school_email,
            'footer_ig'          => $config->footer_ig,
            'footer_yt'          => $config->footer_yt,
            'footer_fb'          => $config->footer_fb,
            'footer_linkedin'    => $config->footer_linkedin,
        ],
    ]);
}

   public function events(Request $request): JsonResponse
{
    $query = Event::where('status', 'publish');

    if ($request->filled('search')) {
        $query->where('title', 'ILIKE', '%' . $request->search . '%');
    }

    $sortBy = $request->get('sort_by', 'created_at');
    $sort = $request->get('sort', 'desc');

    $query->orderBy($sortBy, $sort);

    if ($request->filled('limit')) {
        $events = $query->paginate($request->limit);

        return response()->json([
            'success' => true,
            'total' => $events->total(),
            'totalPage' => $events->lastPage(),
            'data' => $events->items(),
        ]);
    }

    return response()->json([
        'success' => true,
        'data' => $query->get(),
    ]);
}

public function eventDetail(string $value): JsonResponse
{
    try {

        $event = Event::where(function ($query) use ($value) {

                if (is_numeric($value)) {
                    $query->where('id', $value);
                }

                $query->orWhere('slug', $value);

            })
            ->where('status', 'publish')
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $event
        ]);

    } catch (Exception $e) {

        return response()->json([
            'success' => false,
            'message' => 'Event tidak ditemukan.'
        ], 404);

    }
}

public function news(Request $request): JsonResponse
{
    $query = News::where('status', 'publish')->with('category');

    if ($request->filled('search')) {
        $query->where('title', 'ILIKE', '%' . $request->search . '%');
    }

    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
    }

    $sortBy = $request->get('sort_by', 'created_at');
    $sort = $request->get('sort', 'desc');

    $query->orderBy($sortBy, $sort);

    $transform = function ($item) {
        return [
            'id' => $item->id,
            'slug' => $item->slug,
            'title' => $item->title,
            'content' => $item->content,
            'author' => $item->author,
            'img_cover' => $item->img_cover
                ? url('api/file/news/img_cover/' . $item->id . '/' . time())
                : null,
            'status' => $item->status,
            'category_id' => $item->category_id,
            'category_name' => $item->category?->name,
            'created_at' => $item->created_at,
        ];
    };

    if ($request->filled('limit')) {
        $news = $query->paginate($request->limit);

        return response()->json([
            'success' => true,
            'total' => $news->total(),
            'totalPage' => $news->lastPage(),
            'data' => $news->through($transform)->items(),
        ]);
    }

    return response()->json([
        'success' => true,
        'data' => $query->get()->map($transform)->values(),
    ]);
}

// Endpoint baru — daftar kategori aktif buat filter chip
public function newsCategories(): JsonResponse
{
    $categories = NewsCategories::where('active', true)
        ->orderBy('name')
        ->get(['id', 'name']);

    return response()->json([
        'success' => true,
        'data' => $categories,
    ]);
}

public function newsDetail(string $value): JsonResponse
{
    try {

        $news = News::where(function ($query) use ($value) {
                if (is_numeric($value)) {
                    $query->where('id', $value);
                }
                $query->orWhere('slug', $value);
            })
            ->where('status', 'publish')
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $news->id,
                'slug' => $news->slug,
                'title' => $news->title,
                'content' => $news->content,
                'author' => $news->author,
                'img_cover' => $news->img_cover
                    ? url('api/file/news/img_cover/' . $news->id . '/' . time())
                    : null,
                'status' => $news->status,
                'created_at' => $news->created_at,
            ],
        ]);

    } catch (Exception $e) {

        return response()->json([
            'success' => false,
            'message' => 'Berita tidak ditemukan.'
        ], 404);

    }
}
  public function feedbackCategories(): JsonResponse
{
    $categories = FeedbackCategories::where('status', true)
        ->get(['id', 'category_name']);

    return response()->json([
        'success' => true,
        'data' => $categories,
    ]);
}

    public function submitFeedback(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'sender_name' => 'nullable|string|max:255',
                'type'        => 'required|boolean',
                'category_id' => 'required|exists:feedback_categories,id',
                'message'     => 'required|string',
            ], [
                'type.required'        => 'Jenis feedback wajib dipilih.',
                'category_id.required' => 'Kategori feedback wajib dipilih.',
                'message.required'     => 'Pesan tidak boleh kosong.',
            ]);

            $feedback = Feedbacks::create([
                'sender_name' => $request->sender_name,
                'type'        => $request->type,
                'category_id' => $request->category_id,
                'message'     => $request->message,
                'created_by'  => Auth::guard('api')->id(), 
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Terima kasih atas masukannya!',
                'data'    => $feedback,
            ], 201);

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

    // GET Voting aktif (card)
    public function voting(Request $request)
{
    $search = $request->query('search');
    $limit  = $request->query('limit');
    $sort   = $request->query('sort', 'asc');
    $sortBy = $request->query('sort_by', 'end_date');

    $query = Voting::where('status_code', true)
        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'ilike', "%{$search}%")
                    ->orWhere('description', 'ilike', "%{$search}%");
            });
        })
        ->withCount('votingCandidates')
        ->orderBy($sortBy, $sort);

    $transform = function ($item) {

        $totalVotes = VotingLogs::where('voting_id', $item->id)->count();

        return [
            'id' => $item->id,
            'slug' => $item->slug,
            'title' => $item->title,
            'description' => $item->description,
           'img_cover' => $item->img_cover ? url('api/file/voting/img_cover/' . $item->id . '/' . time()) : null,
            'start_date' => $item->start_date?->format('d M Y H:i'),
            'end_date' => $item->end_date?->format('d M Y H:i'),
            'is_highlight' => $item->is_highlight,

            'candidates' => $item->votingCandidates->map(function ($candidate) use ($item, $totalVotes) {

                $voteCount = VotingLogs::where('voting_id', $item->id)
                    ->where('candidate_id', $candidate->id)
                    ->count();

                return [
                    'id' => $candidate->id,
                    'order' => $candidate->order,
                    'title' => $candidate->title,
                    'description' => $candidate->description,
                    'img_cover' => $candidate->img_cover ? url('api/file/voting_candidates/img_cover/' . $candidate->id . '/' . time()) : null,

                    'vote_count' => $voteCount,

                    'percentage' => $totalVotes > 0
                        ? round(($voteCount / $totalVotes) * 100, 1)
                        : 0
                ];
            })->values()
        ];
    };

    if ($limit === null || $limit === 'all') {
        $votings = $query->get();

        return response()->json([
            'success'     => true,
            'total'       => $votings->count(),
            'totalPage'   => 1,
            'currentPage' => 1,
            'data'        => $votings->map($transform)->values(),
        ]);
    }

    $votings = $query->paginate((int) $limit);

    return response()->json([
        'success'     => true,
        'total'       => $votings->total(),
        'totalPage'   => $votings->lastPage(),
        'currentPage' => $votings->currentPage(),
        'data'        => $votings->through($transform)->items(),
    ]);
}

   public function votingDetail(string $slug): JsonResponse
{
    try {

        $voting = Voting::where('slug', $slug)
            ->where('status_code', true)
            ->with('votingCandidates')
            ->firstOrFail();

        $totalVotes = VotingLogs::where('voting_id', $voting->id)->count();

        $candidates = $voting->votingCandidates->map(function ($candidate) use ($voting, $totalVotes) {
            $voteCount = VotingLogs::where('voting_id', $voting->id)
                ->where('candidate_id', $candidate->id)
                ->count();

            return [
                'id' => $candidate->id,
                'order' => $candidate->order,
                'title' => $candidate->title,
                'description' => $candidate->description,
                'img_cover' => $candidate->img_cover ? url('api/file/voting_candidates/img_cover/' . $candidate->id . '/' . time()) : null,

                'vote_count' => $voteCount,

                'percentage' => $totalVotes > 0
                    ? round(($voteCount / $totalVotes) * 100, 1)
                    : 0,
            ];

        })->values();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $voting->id,
                'slug' => $voting->slug,
                'title' => $voting->title,
                'description' => $voting->description,
                'img_cover' => $voting->img_cover ? url('api/file/voting/img_cover/' . $voting->id . '/' . time()) : null,
                'start_date' => $voting->start_date?->format('d M Y H:i'),
                'end_date' => $voting->end_date?->format('d M Y H:i'),
                'is_highlight' => $voting->is_highlight,
                'candidates' => $candidates,
            ],
        ]);

    } catch (Exception $e) {
    Log::error('votingDetail error: ' . $e->getMessage());
    return response()->json([
        'success' => false,
        'message' => 'Voting tidak ditemukan.',
    ], 404);
}
}

   public function majors(): JsonResponse
{
    $majors = Major::where('status_code', true)
        ->with(['competencies' => function ($q) {
            $q->where('status_code', true)->orderBy('id')->limit(3);
        }])
        ->orderBy('id')
        ->get()
        ->map(function ($m) {
            return [
                'id' => $m->id,
                'slug' => $m->slug,
                'code' => $m->code,
                'major_name' => $m->major_name,
                'summary' => $m->summary,
                'img_logo' => $m->img_logo ? url('api/file/major/img_logo/' . $m->id . '/' . time()) : null,
                'competencies' => $m->competencies->pluck('competent_name')->values(),
            ];
        });

    return response()->json([
        'success' => true,
        'data' => $majors,
    ]);
}

   public function majorDetail(string $slug): JsonResponse
{
    $major = Major::where('slug', $slug)
        ->where('status_code', true)
        ->with(['competencies', 'galleries'])
        ->first();

    if (!$major) {
        return response()->json([
            'success' => false,
            'message' => 'Jurusan tidak ditemukan.',
        ], 404);
    }

    return response()->json([
        'success' => true,
        'data' => [
            'id' => $major->id,
            'slug' => $major->slug,
            'code' => $major->code,
            'major_name' => $major->major_name,
            'summary' => $major->summary,
            'total_classes' => $major->total_classes,
            'major_duration' => $major->major_duration,
            'full_description' => $major->full_description,
            'img_logo' => $this->fileUrl('major', 'img_logo', $major->id, $major->img_logo),
            'competencies' => $major->competencies->sortBy('id')->map(fn ($c) => [
                'id' => $c->id,
                'competent_name' => $c->competent_name,
                'description' => $c->description,
            ])->values(),
            'galleries' => $major->galleries->sortBy('id')->map(fn ($g) => [
                'id' => $g->id,
                'img_cover' => $this->fileUrl('major_gallery', 'img_cover', $g->id, $g->img_cover),
                'description' => $g->description,
            ])->values(),
        ],
    ]);
}

private function fileUrl(string $model, string $field, int $id, ?string $value): ?string
{
    if (!$value) {
        return null;
    }

    // data uji yang isinya link eksternal dipakai apa adanya
    if (str_starts_with($value, 'http')) {
        return $value;
    }

    return url("api/file/{$model}/{$field}/{$id}/" . time());
}
}