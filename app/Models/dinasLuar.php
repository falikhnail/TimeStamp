<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dinasLuar extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    
    public function Karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function Shift()
    {
        return $this->belongsTo(Shift::class);
    }
}
