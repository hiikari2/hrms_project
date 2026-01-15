<?php

// File: app/Services/AhpService.php
namespace App\Services;

use App\Models\AhpSession;
use App\Models\PairwiseComparison;
use App\Models\EmployeeEvaluation;
use App\Models\AhpResult;
use App\Models\Kriteria;
use Illuminate\Support\Facades\DB;

class AhpService
{
    private $randomIndexTable = [
        1 => 0,
        2 => 0,
        3 => 0.58,
        4 => 0.90,
        5 => 1.12,
        6 => 1.24,
        7 => 1.32,
        8 => 1.41,
        9 => 1.45,
        10 => 1.49
    ];

    public function calculateWeights($sessionId)
    {
        $session = AhpSession::findOrFail($sessionId);
        $comparisons = PairwiseComparison::where('session_id', $sessionId)
            ->with(['kriteriaA', 'kriteriaB'])
            ->get();

        $kriteriaList = Kriteria::where('is_active', true)->orderBy('urutan')->get();
        $n = $kriteriaList->count();

        $matrix = $this->buildPairwiseMatrix($kriteriaList, $comparisons);
        $normalizedMatrix = $this->normalizeMatrix($matrix);
        $weights = $this->calculatePriorityWeights($normalizedMatrix);
        $consistencyRatio = $this->calculateConsistencyRatio($matrix, $weights, $n);

        return [
            'weights' => $weights,
            'consistency_ratio' => $consistencyRatio,
            'is_consistent' => $consistencyRatio < 0.1
        ];
    }

    private function buildPairwiseMatrix($kriteriaList, $comparisons)
    {
        $n = $kriteriaList->count();
        $matrix = array_fill(0, $n, array_fill(0, $n, 1));

        foreach ($comparisons as $comparison) {
            $indexA = $kriteriaList->search(fn($k) => $k->id == $comparison->kriteria_a_id);
            $indexB = $kriteriaList->search(fn($k) => $k->id == $comparison->kriteria_b_id);

            if ($indexA !== false && $indexB !== false) {
                $matrix[$indexA][$indexB] = $comparison->nilai_perbandingan;
                $matrix[$indexB][$indexA] = 1 / $comparison->nilai_perbandingan;
            }
        }

        return $matrix;
    }

    private function normalizeMatrix($matrix)
    {
        $n = count($matrix);
        $columnSums = array_fill(0, $n, 0);

        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $columnSums[$j] += $matrix[$i][$j];
            }
        }

        $normalized = [];
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $normalized[$i][$j] = $matrix[$i][$j] / $columnSums[$j];
            }
        }

        return $normalized;
    }

    private function calculatePriorityWeights($normalizedMatrix)
    {
        $n = count($normalizedMatrix);
        $weights = [];

        for ($i = 0; $i < $n; $i++) {
            $rowSum = array_sum($normalizedMatrix[$i]);
            $weights[$i] = $rowSum / $n;
        }

        return $weights;
    }

    private function calculateConsistencyRatio($matrix, $weights, $n)
    {
        $weightedSum = array_fill(0, $n, 0);

        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $weightedSum[$i] += $matrix[$i][$j] * $weights[$j];
            }
        }

        $lambdaMax = 0;
        for ($i = 0; $i < $n; $i++) {
            $lambdaMax += $weightedSum[$i] / $weights[$i];
        }
        $lambdaMax /= $n;

        $ci = ($lambdaMax - $n) / ($n - 1);
        $ri = $this->randomIndexTable[$n] ?? 1.49;
        $cr = $ci / $ri;

        return $cr;
    }

    public function calculateFinalScores($sessionId)
    {
        $weightsResult = $this->calculateWeights($sessionId);
        
        if (!$weightsResult['is_consistent']) {
            throw new \Exception('Consistency ratio is too high. Please review your comparisons.');
        }

        $kriteriaList = Kriteria::where('is_active', true)->orderBy('urutan')->get();
        $evaluations = EmployeeEvaluation::where('session_id', $sessionId)
            ->with(['employee', 'kriteria', 'subKriteria'])
            ->get()
            ->groupBy('employee_id');

        $results = [];

        foreach ($evaluations as $employeeId => $employeeEvals) {
            $finalScore = 0;
            $details = [];

            foreach ($kriteriaList as $index => $kriteria) {
                $eval = $employeeEvals->firstWhere('kriteria_id', $kriteria->id);
                
                if ($eval) {
                    $kriteriaScore = $eval->nilai * $weightsResult['weights'][$index];
                    $finalScore += $kriteriaScore;
                    
                    $details[] = [
                        'kriteria' => $kriteria->nama,
                        'nilai' => $eval->nilai,
                        'bobot' => $weightsResult['weights'][$index],
                        'skor' => $kriteriaScore
                    ];
                }
            }

            $results[] = [
                'employee_id' => $employeeId,
                'nilai_akhir' => $finalScore,
                'details' => $details
            ];
        }

        usort($results, fn($a, $b) => $b['nilai_akhir'] <=> $a['nilai_akhir']);

        $this->saveResults($sessionId, $results);

        return $results;
    }

    private function saveResults($sessionId, $results)
    {
        AhpResult::where('session_id', $sessionId)->delete();

        foreach ($results as $index => $result) {
            $rekomendasi = $this->getRekomendasi($result['nilai_akhir']);

            AhpResult::updateOrCreate(
                [
                    'session_id' => $sessionId,
                    'employee_id' => $result['employee_id']
                ],
                [
                    'nilai_akhir' => $result['nilai_akhir'],
                    'ranking' => $index + 1,
                    'rekomendasi' => $rekomendasi,
                    'detail_perhitungan' => $result['details']
                ]
            );
        }
    }

    private function getRekomendasi($nilai)
    {
        if ($nilai >= 0.85) return 'Sangat Layak';
        if ($nilai >= 0.70) return 'Layak';
        if ($nilai >= 0.55) return 'Cukup Layak';
        return 'Tidak Layak';
    }
}