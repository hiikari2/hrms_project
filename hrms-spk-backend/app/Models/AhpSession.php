<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AhpSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_session',
        'deskripsi',
        'periode_awal',
        'periode_akhir',
        'status',
        'created_by'
    ];

    protected $casts = [
        'periode_awal' => 'date',
        'periode_akhir' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function pairwiseComparisons()
    {
        return $this->hasMany(PairwiseComparison::class, 'session_id');
    }

    public function evaluations()
    {
        return $this->hasMany(EmployeeEvaluation::class, 'session_id');
    }

    public function results()
    {
        return $this->hasMany(AhpResult::class, 'session_id');
    }
}
