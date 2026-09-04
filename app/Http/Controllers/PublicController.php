<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Event;
use App\Models\Feedbacks;
use App\Models\FeedbackCategories;
use App\Models\GlobalConfig;
use App\Models\Major;
use App\Models\Voting;
use App\Models\VotingLogs;
use App\Models\News;
use Exception;
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
            ->get(['id', 'title', 'img_cover', 'url']);

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

    return response()->json([
        'success' => true,
        'data'    => [
            'motto'                => $config->motto,
            'profile_title'        => $config->profile_title,
            'profile_description'  => $config->profile_description,
            'img_profile_1'        => $config->img_profile_1,
            'img_profile_2'        => $config->img_profile_2,
            'video_profile'        => $config->video_profile,
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
    $query = News::where('status', 'publish');

    if ($request->filled('search')) {
        $query->where('title', 'ILIKE', '%' . $request->search . '%');
    }

    $sortBy = $request->get('sort_by', 'created_at');
    $sort = $request->get('sort', 'desc');

    $query->orderBy($sortBy, $sort);

    if ($request->filled('limit')) {
        $news = $query->paginate($request->limit);

        return response()->json([
            'success' => true,
            'total' => $news->total(),
            'totalPage' => $news->lastPage(),
            'data' => $news->items(),
        ]);
    }

    return response()->json([
        'success' => true,
        'data' => $query->get(),
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
            'data' => $news,
        ]);

    } catch (Exception $e) {

        return response()->json([
            'success' => false,
            'message' => 'Berita tidak ditemukan.'
        ], 404);

    }
}

public function feedbacks()
{
    $data = DB::table('feedbacks')
        ->orderBy('created_at', 'desc')
        ->get();

    return response()->json([
        'success' => true,
        'data' => $data,
    ]);
}
   public function feedbackCategories(): JsonResponse
{
   $categories = FeedbackCategories::get([
    'id',
    'category_name'
]);

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
            ->withCount('votingCandidate')
            ->orderBy($sortBy, $sort);

       $transform = function ($item) {

    $totalVotes = VotingLogs::where('voting_id', $item->id)->count();

    return [
        'id' => $item->id,
        'slug' => $item->slug,
        'title' => $item->title,
        'description' => $item->description,
        'img_cover' => $item->img_cover,
        'start_date' => $item->start_date?->format('d M Y H:i'),
        'end_date' => $item->end_date?->format('d M Y H:i'),
        'is_highlight' => $item->is_highlight,

        'candidates' => $item->votingCandidate->map(function ($candidate) use ($item, $totalVotes) {

            $voteCount = VotingLogs::where('voting_id', $item->id)
                ->where('candidate_id', $candidate->id)
                ->count();

            return [
                'id' => $candidate->id,
                'order' => $candidate->order,
                'title' => $candidate->title,
                'description' => $candidate->description,
                'img_cover' => $candidate->img_cover,

                'vote_count' => $voteCount,

                'percentage' => $totalVotes > 0
                    ? round(($voteCount / $totalVotes) * 100, 1)
                    : 0
            ];
        })->values()
    ];
};

        // Kalau limit tidak dikirim (null) atau eksplisit 'all', tampilkan semua data
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
            ->with('votingCandidate')
            ->firstOrFail();

        $totalVotes = VotingLogs::where('voting_id', $voting->id)->count();

        $candidates = $voting->votingCandidate->map(function ($candidate) use ($voting, $totalVotes) {

            $voteCount = VotingLogs::where('voting_id', $voting->id)
                ->where('candidate_id', $candidate->id)
                ->count();

            return [
                'id' => $candidate->id,
                'order' => $candidate->order,
                'title' => $candidate->title,
                'description' => $candidate->description,
                'img_cover' => $candidate->img_cover,

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
                'img_cover' => $voting->img_cover,
                'start_date' => $voting->start_date?->format('d M Y H:i'),
                'end_date' => $voting->end_date?->format('d M Y H:i'),
                'is_highlight' => $voting->is_highlight,
                'candidates' => $candidates,
            ],
        ]);

    } catch (Exception $e) {

        return response()->json([
            'success' => false,
            'message' => 'Voting tidak ditemukan.',
        ], 404);

    }
}

    public function majors(): JsonResponse
    {
        $majors = Major::where('status_code', true)
            ->orderBy('major_name', 'asc')
            ->get(['id', 'slug', 'img_logo', 'code', 'major_name', 'summary', 'total_classes', 'major_duration']);

        return response()->json([
            'success' => true,
            'data'    => $majors,
        ]);
    }

    public function majorDetail(string $slug): JsonResponse
    {
        try {
            $major = Major::where('slug', $slug)
                ->where('status_code', true)
                ->with(['competents', 'galleries'])
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data'    => [
                    'slug'             => $major->slug,
                    'img_logo'         => $major->img_logo,
                    'code'             => $major->code,
                    'major_name'       => $major->major_name,
                    'summary'          => $major->summary,
                    'total_classes'    => $major->total_classes,
                    'major_duration'   => $major->major_duration,
                    'full_description' => $major->full_description,
                    'competents'       => $major->competents->map(fn ($c) => [
                        'competent_name' => $c->competent_name,
                        'description'    => $c->description,
                    ]),
                    'galleries'        => $major->galleries->map(fn ($g) => [
                        'img_cover'   => $g->img_cover,
                        'description' => $g->description,
                    ]),
                ],
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Jurusan tidak ditemukan.',
            ], 404);
        }
    }
}