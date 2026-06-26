<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    // LIST
    public function index(Request $request)
{
    $query = Event::with([
        'creator:id,fullname',
        'updater:id,fullname'
    ]);

    // SEARCH
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('title', 'ILIKE', "%{$search}%")
            ->orWhere('content', 'ILIKE', "%{$search}%")
            ->orWhere('location', 'ILIKE', "%{$search}%");
        });
    }

    // FILTER STATUS
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    } else {
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

    $allowedSort = [
        'id',
        'title',
        'status',
        'start_date'
    ];

    if (!in_array($sortBy, $allowedSort)) {
        $sortBy = 'id';
    }

    if (!in_array($sort, ['asc', 'desc'])) {
        $sort = 'desc';
    }

    $query->orderBy($sortBy, $sort);

    // PAGINATION
    $limit = (int)$request->get('limit', 10);
    $page = (int)$request->get('page', 1);

    $events = $query
        ->paginate($limit, ['*'], 'page', $page)
        ->through(function ($item) {

            return [

                'id'=>$item->id,
                'slug'=>$item->slug,
                'title'=>$item->title,
                'location'=>$item->location,
                'description'=>$item->description,
                'start_date'=>$item->start_date,
                'end_date'=>$item->end_date,
                'img_cover'=>$item->img_cover,
                'status'=>$item->status,
                'is_highlight'=>$item->is_highlight,
                'created_by_fullname'=>$item->creator?->fullname,
                'updated_by_fullname'=>$item->updater?->fullname,
                'created_at'=>$item->created_at,
                'updated_at'=>$item->updated_at,

            ];
        });

    return response()->json([
        'success'=>true,
        'data'=>$events
    ]);
}

    // CREATE
    public function store(Request $request)
    {
        $request->validate([
            'slug' => 'required|string|unique:events,slug',
            'title' => 'required|string',
            'content' => 'required|string'
        ]);

        $event = Event::create([
            'slug' => $request->input('slug'),
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'location' => $request->input('location'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'img_cover' => $request->input('img_cover'),
            'status' => $request->input('status', 'draft'),
            'is_highlight' => $request->input('is_highlight', false),
            'created_by' => 1,
            'updated_by' => 1
        ]);

        return response()->json([
            'success' => true,
            'data' => $event
        ]);
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $event->update([
            'slug' => $request->input('slug'),
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'location' => $request->input('location'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'img_cover' => $request->input('img_cover'),
            'status' => $request->input('status'),
            'is_highlight' => $request->input('is_highlight'),
            'updated_by' => 1
        ]);

        return response()->json([
            'success' => true,
            'data' => $event
        ]);
    }

    // DELETE
    public function delete($id)
    {
        $event = Event::findOrFail($id);

        $event->delete();

        return response()->json([
            'success' => true,
            'message' => 'Event deleted'
        ]);
    }
}