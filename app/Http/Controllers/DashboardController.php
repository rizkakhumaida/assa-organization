<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity; // pastikan model Activity sudah ada
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data user login
        $user = Auth::user();

        // Hitung data-data statistik
        $jumlahKegiatan = Activity::count();
        $jumlahBeasiswa = 0; // nanti bisa diubah kalau sudah ada tabel beasiswa
        $jumlahKerjasama = 0;
        $jumlahPoin = 0;

        // Ambil kegiatan terbaru sebagai rekomendasi
        $rekomendasi = Activity::orderBy('created_at', 'desc')
                            ->take(3)
                            ->get();

        // Kirim data ke view
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
