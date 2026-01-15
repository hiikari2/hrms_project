<?php

// File: app/Models/Kriteria.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;

    protected $table = 'kriteria';

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'urutan',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function subKriteria()
    {
        return $this->hasMany(SubKriteria::class);
    }

    public function pairwiseComparisonsA()
    {
        return $this->hasMany(PairwiseComparison::class, 'kriteria_a_id');
    }

    public function pairwiseComparisonsB()
    {
        return $this->hasMany(PairwiseComparison::class, 'kriteria_b_id');
    }

    public function evaluations()
    {
        return $this->hasMany(EmployeeEvaluation::class);
    }
}
