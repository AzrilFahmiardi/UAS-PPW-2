<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Pekerjaan;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    public function index() {
        // Statistik gender
        $genderCounts = Pegawai::select('gender', DB::raw('count(*) as total'))
            ->groupBy('gender')
            ->pluck('total','gender')
            ->toArray();

        // Top 5 pekerjaan berdasarkan jumlah pegawai
        $topPekerjaan = Pekerjaan::withCount('pegawai')
            ->orderBy('pegawai_count', 'desc')
            ->take(5)
            ->get(['nama', 'pegawai_count']);

        return view('index', compact('genderCounts','topPekerjaan'));
    }
}
