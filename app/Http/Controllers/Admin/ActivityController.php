<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 📋 INDEX — Daftar kegiatan (search + filter + paginate)
    |-------------------------------------------------------------------------- */
    public function index(Request $request)
    {
        $query = Activity::query();

        // ✅ Support parameter "q" ATAU "search" (biar kompatibel dengan view yang berbeda)
        $term = $request->filled('q') ? $request->q : ($request->search ?? null);

        if (!empty($term)) {
            // pakai scopeSearch yang sudah Anda buat
            $query->search($term);
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

        // ✅ Urutkan rapi
        $activities = $query->latest('start_at')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        // kirim juga $term jika view butuh menampilkan isi search
        return view('admin.activities.index', compact('activities', 'term'));
    }

    /*
    |-------------------------------------------------------------------------- */
    public function create()
    {
        return view('admin.activities.create');
    }

    /*
    |--------------------------------------------------------------------------
    | 💾 STORE — Simpan kegiatan baru
    |-------------------------------------------------------------------------- */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string', // ✅ disesuaikan dengan form (boleh kosong)
            'start_at'     => 'required|date',
            'end_at'       => 'nullable|date|after_or_equal:start_at',
            'location'     => 'nullable|string|max:255', // ✅ disesuaikan dengan form (boleh kosong)
            'category'     => 'nullable|string|max:100', // ✅ form Anda belum punya field ini
            'is_published' => 'nullable',
        ]);

        // ✅ checkbox: kalau tidak dicentang, field tidak terkirim → set manual true/false
        $data['is_published'] = $request->has('is_published');

        // ✅ jika category tidak dikirim, kasih default agar konsisten
        $data['category'] = $data['category'] ?? 'Lainnya';

        Activity::create($data);

        return redirect()
            ->route('admin.activities.index')
            ->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    /*
    |-------------------------------------------------------------------------- */
    public function show(Activity $activity)
    {
        $activity->load('participants');
        return view('admin.activities.show', compact('activity'));
    }

    /*
    |-------------------------------------------------------------------------- */
    public function edit(Activity $activity)
    {
        return view('admin.activities.edit', compact('activity'));
    }

    /*
    |--------------------------------------------------------------------------
    | 🔁 UPDATE — Simpan perubahan
    |-------------------------------------------------------------------------- */
    public function update(Request $request, Activity $activity)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'start_at'     => 'required|date',
            'end_at'       => 'nullable|date|after_or_equal:start_at',
            'location'     => 'nullable|string|max:255',
            'category'     => 'nullable|string|max:100',
            'is_published' => 'nullable',
        ]);

        $data['is_published'] = $request->has('is_published');
        $data['category'] = $data['category'] ?? ($activity->category ?? 'Lainnya');

        $activity->update($data);

        return redirect()
            ->route('admin.activities.index')
            ->with('success', 'Kegiatan berhasil diperbarui.');
    }

    /*
    |-------------------------------------------------------------------------- */
    public function destroy(Activity $activity)
    {
        $activity->delete();

        return redirect()
            ->route('admin.activities.index')
            ->with('success', 'Kegiatan berhasil dihapus.');
    }
}
