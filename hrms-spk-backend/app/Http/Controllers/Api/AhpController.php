<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AhpSession;
use App\Models\AhpPairwiseComparison;
use App\Models\AhpResult;
use App\Models\Kriteria;
use App\Models\AuditLog;
use App\Services\AhpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AhpController extends Controller
{
    protected $ahpService;

    public function __construct(AhpService $ahpService)
    {
        $this->ahpService = $ahpService;
    }

    public function getKriteria()
    {
        $kriteria = Kriteria::active()->get();

        return response()->json([
            'success' => true,
            'data' => $kriteria
        ]);
    }

    public function getSessions(Request $request)
    {
        $query = AhpSession::with('user');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $sessions = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $sessions
        ]);
    }

    public function createSession(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_sesi' => 'required|max:255',
            'deskripsi' => 'nullable',
            'periode' => 'nullable|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $session = AhpSession::create([
                'user_id' => auth()->id(),
                'nama_sesi' => $request->nama_sesi,
                'deskripsi' => $request->deskripsi,
                'periode' => $request->periode,
                'status' => 'draft'
            ]);

            // Audit log
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'CREATE',
                'module' => 'AHP_SESSION',
                'description' => "Membuat sesi AHP: {$session->nama_sesi}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Sesi AHP berhasil dibuat',
                'data' => $session
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function savePairwiseComparisons(Request $request, $id)
    {
        $session = AhpSession::find($id);

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'comparisons' => 'required|array',
            'comparisons.*.kriteria_1_id' => 'required|exists:kriteria,id',
            'comparisons.*.kriteria_2_id' => 'required|exists:kriteria,id',
            'comparisons.*.nilai_perbandingan' => 'required|numeric|min:0.111|max:9'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Hapus comparisons lama
            AhpPairwiseComparison::where('session_id', $id)->delete();

            // Simpan comparisons baru
            foreach ($request->comparisons as $comp) {
                AhpPairwiseComparison::create([
                    'session_id' => $id,
                    'kriteria_1_id' => $comp['kriteria_1_id'],
                    'kriteria_2_id' => $comp['kriteria_2_id'],
                    'nilai_perbandingan' => $comp['nilai_perbandingan']
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Perbandingan berpasangan berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function calculate($id)
    {
        $session = AhpSession::find($id);

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi tidak ditemukan'
            ], 404);
        }

        try {
            $result = $this->ahpService->processAHP($id);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getResults($id)
    {
        $session = AhpSession::with(['results' => function($query) {
            $query->with('employee')->orderBy('ranking', 'asc');
        }])->find($id);

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'session' => $session,
                'results' => $session->results,
                'is_consistent' => $session->is_consistent,
                'consistency_ratio' => $session->consistency_ratio
            ]
        ]);
    }
}