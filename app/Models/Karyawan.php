<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // Import the correct class
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class Karyawan extends Authenticatable // Extend Authenticatable instead of Model
{
    use HasFactory, Notifiable, HasApiTokens; // Include necessary traits

    protected $guarded = ['id'];

    protected $hidden = [
        'password', // Hide password field
    ];

    // Relationships
    public function MappingShift()
    {
        return $this->hasMany(MappingShift::class);
    }
    
    public function dinasLuar()
    {
        return $this->hasMany(dinasLuar::class);
    }
    
    public function Sip()
    {
        return $this->hasMany(Sip::class);
    }

    public function Lembur()
    {
        return $this->hasMany(Lembur::class);
    }

    public function Payroll()
    {
        return $this->hasMany(Payroll::class);
    }

    public function Cuti()
    {
        return $this->hasMany(Cuti::class);
    }

    public function Jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function Lokasi()
    {
        return $this->belongsTo(Lokasi::class);
    }

    public function whatsapp($phoneNumber) {
        if (substr($phoneNumber, 0, 1) == '0') {
            return '62' . substr($phoneNumber, 1);
        }
        return $phoneNumber;
    }
}



