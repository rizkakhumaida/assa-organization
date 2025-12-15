<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Activity;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Statistik untuk admin
        $jumlahKegiatan   = Activity::count();
        $jumlahBeasiswa   = 0; // sesuaikan jika sudah ada tabel beasiswa
        $jumlahKerjasama  = 0;
        $jumlahPoin       = 0;

        $rekomendasi = Activity::latest()->take(3)->get();

        return view('admin.dashboard', compact(
            'user',
            'jumlahKegiatan',
            'jumlahBeasiswa',
            'jumlahKerjasama',
            'jumlahPoin',
            'rekomendasi'
        ));
    }
}
