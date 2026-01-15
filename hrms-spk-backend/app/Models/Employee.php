<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nip',
        'nama',
        'email',
        'jabatan',
        'departemen',
        'tanggal_masuk',
        'status',
        'foto'
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
    ];

    public function evaluations()
    {
        return $this->hasMany(EmployeeEvaluation::class);
    }

    public function ahpResults()
    {
        return $this->hasMany(AhpResult::class);
    }
}