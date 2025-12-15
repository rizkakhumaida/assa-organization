<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActivityController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 📋 INDEX — Daftar kegiatan (search + filter + paginate)
    |--------------------------------------------------------------------------
    | Query params:
    | - q         : string (cari title/location/description)
    | - status    : upcoming|ongoing|past
    | - from,to   : Y-m-d (filter start_at range)
    | - sort      : latest|oldest|start_asc|start_desc
    | - per_page  : 6..100 (default 9)
    */
    public function index(Request $request)
    {
        $query = Activity::query();

        // ✅ Search functionality
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // ✅ Filter by status
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'upcoming':
                    $query->upcoming();
                    break;
                case 'ongoing':
                    $query->ongoing();
                    break;
                case 'past':
                    $query->past();
                    break;
                case 'published':
                    $query->published();
                    break;
            }
        }

        // ✅ Perbaikan: Gunakan latest() atau orderBy()
        $activities = $query->latest('start_at') // Order by start_at descending
                           ->latest('id')        // Then by id descending
                           ->paginate(10)
                           ->withQueryString();

        // ✅ Alternative menggunakan orderBy manual:
        // $activities = $query->orderByDesc('start_at')
        //                    ->orderByDesc('id')
        //                    ->paginate(10)
        //                    ->withQueryString();

        return view('admin.activities.index', compact('activities'));
    }

    /*
    |--------------------------------------------------------------------------
    | 🆕 CREATE — Form tambah
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        return view('admin.activities.create');
    }

    /*
    |--------------------------------------------------------------------------
    | 💾 STORE — Simpan kegiatan baru
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_at' => 'required|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'location' => 'required|string|max:255',
            'category' => 'required|string',
            'is_published' => 'boolean',
        ]);

        Activity::create($data);

        return redirect()
            ->route('admin.activities.index')
            ->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    /*
    |--------------------------------------------------------------------------
    | 🔍 SHOW — Detail + rekomendasi
    |--------------------------------------------------------------------------
    */
    public function show(Activity $activity)
    {
        // ✅ Load participants relationship
        $activity->load('participants');
        return view('admin.activities.show', compact('activity'));
    }

    /*
    |--------------------------------------------------------------------------
    | ✏️ EDIT — Form edit
    |--------------------------------------------------------------------------
    */
    public function edit(Activity $activity)
    {
        return view('admin.activities.edit', compact('activity'));
    }

    /*
    |--------------------------------------------------------------------------
    | 🔁 UPDATE — Simpan perubahan
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, Activity $activity)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_at' => 'required|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'location' => 'required|string|max:255',
            'is_published' => 'boolean',
        ]);

        $activity->update($data);

        return redirect()
            ->route('admin.activities.index')
            ->with('success', 'Kegiatan berhasil diperbarui.');
    }

    /*
    |--------------------------------------------------------------------------
    | 🗑️ DESTROY — Hapus
    |--------------------------------------------------------------------------
    */
    public function destroy(Activity $activity)
    {
        $activity->delete();

        return redirect()
            ->route('admin.activities.index')
            ->with('success', 'Kegiatan berhasil dihapus.');
    }
}
