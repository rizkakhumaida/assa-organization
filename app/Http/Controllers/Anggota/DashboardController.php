<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Activity;
use App\Models\Achievement;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ✅ Pastikan user login
        if (!$user) {
            return redirect()->route('login');
        }

        /**
         * =========================
         * DATA KEGIATAN USER
         * =========================
         */
        $userActivities = $user->activities()
            ->withPivot('status', 'registered_at')
            ->orderByDesc('start_at')
            ->limit(5)
            ->get();

        /**
         * =========================
         * STATISTIK KEGIATAN
         * =========================
         */
        $totalActivities = $user->activities()->count();

        $attendedActivities = $user->activities()
            ->wherePivot('status', 'attended')
            ->count();

        $upcomingActivities = $user->activities()
            ->where('start_at', '>', now())
            ->count();

        /**
         * =========================
         * STATISTIK PRESTASI
         * =========================
         * (Dari tabel penyetoran prestasi)
         */
        $totalPrestasi = Achievement::where('user_id', $user->id)->count();

        /**
         * =========================
         * FINAL STATS ARRAY
         * =========================
         */
        $stats = [
            'total_activities'   => $totalActivities,
            'attended_activities'=> $attendedActivities,
            'upcoming_activities'=> $upcomingActivities,
            'total_prestasi'     => $totalPrestasi,
        ];

        return view('anggota.dashboard', compact(
            'userActivities',
            'stats',
            'totalPrestasi'
        ));
    }
}
