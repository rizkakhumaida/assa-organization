<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ✅ Check if user is authenticated
        if (!$user) {
            return redirect()->route('login');
        }

        // ✅ Menggunakan relationship activities
        $userActivities = $user->activities()->latest()->limit(5)->get();

        // ✅ Statistik kegiatan - fix field name dari 'date' ke 'start_at'
        $stats = [
            'total_activities' => $user->activities()->count(),
            'attended_activities' => $user->activities()->wherePivot('status', 'attended')->count(),
            'upcoming_activities' => Activity::where('start_at', '>', now())->whereHas('participants', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count(),
        ];

        return view('anggota.dashboard', compact('userActivities', 'stats'));
    }
}
