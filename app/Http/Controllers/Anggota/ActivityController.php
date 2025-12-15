<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * List semua kegiatan + filter + search
     */
    public function index(Request $request)
    {
        $query = Activity::query();

        // ===== FILTER KATEGORI =====
        $categories = ['Semua', 'Seminar', 'Outbound', 'Kunjungan', 'Event Olahraga'];
        if ($request->category && in_array($request->category, $categories) && $request->category !== 'Semua') {
            $query->where('category', $request->category);
        }

        // ===== FILTER PENCARIAN =====
        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $activities = $query->latest()->paginate(10);

        // Kirim juga daftar kategori ke view
        return view('anggota.activities.index', compact('activities', 'categories'));
    }

    /**
     * Detail satu kegiatan
     */
    public function show(Activity $activity)
    {
        return view('anggota.activities.show', compact('activity'));
    }

    /**
     * FORM PENDAFTARAN KEGIATAN
     */
    public function create(Activity $activity)
    {
        return view('anggota.activities.create', compact('activity'));
    }

    /**
     * HANDLE PENDAFTARAN KEGIATAN
     */
    public function register(Request $request, Activity $activity)
    {
        $user = Auth::user();

        // contoh simpan ke pivot (jika tabel pivot sudah ada)
        $user->activities()->syncWithoutDetaching([$activity->id]);

        return redirect()->route('anggota.activities.show', $activity->id)->with('success', 'Anda berhasil mendaftar kegiatan!');
    }

    /**
     * Contoh method join (tidak dipakai lagi jika register sudah ada)
     */
    public function join(Activity $activity)
    {
        $user = Auth::user();
        $user->activities()->syncWithoutDetaching([$activity->id]);

        return redirect()->route('anggota.activities.show', $activity)->with('success', 'Berhasil mendaftar pada kegiatan.');
    }
}
