<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AhpResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'employee_id',
        'nilai_akhir',
        'ranking',
        'rekomendasi',
        'detail_perhitungan'
    ];

    protected $casts = [
        'nilai_akhir' => 'decimal:6',
        'detail_perhitungan' => 'array',
    ];

    public function session()
    {
        return $this->belongsTo(AhpSession::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
