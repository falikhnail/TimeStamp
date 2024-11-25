<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    
    public function Karyawan()
    {
        return $this->hasMany(Karyawan::class);
    }
    public function User()
    {
        return $this->hasMany(User::class);
    }

    public function CreatedBy()
    {
        return $this->belongsTo(Karyawan::class, 'created_by');
    }
}
