<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PairwiseComparison extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'kriteria_a_id',
        'kriteria_b_id',
        'nilai_perbandingan'
    ];

    protected $casts = [
        'nilai_perbandingan' => 'decimal:2',
    ];

    public function session()
    {
        return $this->belongsTo(AhpSession::class);
    }

    public function kriteriaA()
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_a_id');
    }

    public function kriteriaB()
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_b_id');
    }
}
