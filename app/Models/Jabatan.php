<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function Karyawan()
    {
        return $this->hasMany(Karyawan::class);
    }

    public function AutoShift()
    {
        return $this->hasMany(AutoShift::class);
    }

    public function atasan($id)
    {
        return Karyawan::find($id);
    }

    public function anggota($id, $manager)
    {
        return Karyawan::where('jabatan_id', $id)->where('id', '!=', $manager)->orderBy('name', 'ASC')->get();
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
