<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lembur extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function Karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(Karyawan::class, 'approved_by');
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
