<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Golongan extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function Karyawan()
    {
        return $this->hasMany(Karyawan::class);
    }

    public function Tunjangan()
    {
        return $this->hasMany(Tunjangan::class);
    }
}
