<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'employee_id',
        'kriteria_id',
        'sub_kriteria_id',
        'nilai',
        'catatan'
    ];

    protected $casts = [
        'nilai' => 'decimal:2',
    ];

    public function session()
    {
        return $this->belongsTo(AhpSession::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }

    public function subKriteria()
    {
        return $this->belongsTo(SubKriteria::class);
    }
}