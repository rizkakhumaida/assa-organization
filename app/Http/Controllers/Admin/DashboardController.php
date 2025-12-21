<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// ✅ Model sesuai yang Anda tunjukkan
use App\Models\Achievement;
use App\Models\PartnershipProposal;
use App\Models\ScholarshipApplication;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        /**
         * =========================
         * 1) STATISTIK REAL
         * =========================
         */
        $scholarshipsPending = ScholarshipApplication::whereIn('status', ['Pending', 'pending'])->count();
        $partnershipsTotal   = PartnershipProposal::count();
        $achievementsTotal   = Achievement::count();

        $stats = [
            'scholarships_pending' => $scholarshipsPending,
            'partnerships_total'   => $partnershipsTotal,
            'achievements_total'   => $achievementsTotal,
        ];

        /**
         * =========================
         * 2) UNION 3 TABEL (DENGAN NAMA + EMAIL)
         * =========================
         * Output kolom diseragamkan:
         * id, user_id, name, email, type, status, created_at
         *
         * Catatan:
         * - scholarship_applications: ambil full_name/email bila ada, fallback ke users
         * - partnership_proposals: ambil users (karena kolomnya lebih ke instansi)
         * - achievements: ambil peserta bila ada, fallback ke users
         */
        $qBeasiswa = ScholarshipApplication::query()
            ->leftJoin('users as u1', 'scholarship_applications.user_id', '=', 'u1.id')
            ->selectRaw("
                scholarship_applications.id as id,
                scholarship_applications.user_id as user_id,
                COALESCE(NULLIF(scholarship_applications.full_name,''), u1.name) as name,
                COALESCE(NULLIF(scholarship_applications.email,''), u1.email) as email,
                'Beasiswa' as type,
                LOWER(scholarship_applications.status) as status,
                scholarship_applications.created_at as created_at
            ");

        $qKerjasama = PartnershipProposal::query()
            ->leftJoin('users as u2', 'partnership_proposals.user_id', '=', 'u2.id')
            ->selectRaw("
                partnership_proposals.id as id,
                partnership_proposals.user_id as user_id,
                u2.name as name,
                u2.email as email,
                'Kerja Sama' as type,
                LOWER(partnership_proposals.status) as status,
                partnership_proposals.created_at as created_at
            ");

        $qPrestasi = Achievement::query()
            ->leftJoin('users as u3', 'achievements.user_id', '=', 'u3.id')
            ->selectRaw("
                achievements.id as id,
                achievements.user_id as user_id,
                COALESCE(NULLIF(achievements.peserta,''), u3.name) as name,
                u3.email as email,
                'Prestasi' as type,
                LOWER(achievements.status) as status,
                achievements.created_at as created_at
            ");

        // UNION 3 query
        $union = $qBeasiswa
            ->unionAll($qKerjasama)
            ->unionAll($qPrestasi);

        /**
         * =========================
         * 3) FILTER HELPER
         * =========================
         */
        $applyFilters = function ($qb) use ($request) {
            if ($request->filled('type')) {
                $qb->where('type', $request->type);
            }
            if ($request->filled('status')) {
                $qb->where('status', strtolower($request->status));
            }
            return $qb;
        };

        /**
         * =========================
         * 4) EXPORT CSV (REAL + NAME/EMAIL)
         * =========================
         * Klik: ?export=csv
         */
        if ($request->get('export') === 'csv') {
            $baseExport = DB::query()->fromSub($union, 'x');
            $applyFilters($baseExport);

            $rows = $baseExport->orderByDesc('created_at')->get();

            $filename = 'pengajuan_terbaru_' . now()->format('Ymd_His') . '.csv';

            return response()->streamDownload(function () use ($rows) {
                $out = fopen('php://output', 'w');
                fputcsv($out, ['id', 'user_id', 'name', 'email', 'type', 'status', 'created_at']);

                foreach ($rows as $r) {
                    fputcsv($out, [
                        $r->id,
                        $r->user_id,
                        $r->name,
                        $r->email,
                        $r->type,
                        $r->status,
                        $r->created_at,
                    ]);
                }
                fclose($out);
            }, $filename, [
                'Content-Type' => 'text/csv; charset=UTF-8',
            ]);
        }

        /**
         * =========================
         * 5) PENGAJUAN TERBARU (LIST)
         * =========================
         */
        $baseList = DB::query()->fromSub($union, 'x');
        $applyFilters($baseList);

        $recent_applications = $baseList
            ->orderByDesc('created_at')
            ->paginate(5)
            ->withQueryString();

        /**
         * =========================
         * 6) TREND BULANAN (REAL) - AMAN only_full_group_by
         * =========================
         * PENTING:
         * - Pakai builder fresh (jangan reuse yang ada orderBy/paginate)
         */
        $year = now()->year;

        $baseTrend = DB::query()->fromSub($union, 'x');
        $applyFilters($baseTrend);

        $trendRows = $baseTrend
            ->selectRaw('MONTH(created_at) as m, COUNT(*) as c')
            ->whereYear('created_at', $year)
            ->groupBy('m')
            ->orderBy('m')
            ->get();

        $trend = collect(range(1, 12))->mapWithKeys(function ($m) use ($trendRows) {
            $row = $trendRows->firstWhere('m', $m);
            return [$m => $row ? (int) $row->c : 0];
        });

        return view('admin.dashboard', compact(
            'user',
            'stats',
            'trend',
            'recent_applications'
        ));
    }
}
