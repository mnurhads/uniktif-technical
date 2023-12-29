<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'email',
        'phone',
    ];


    // membuat relasi ke tabel hobby
    public function hobbys() {
        return $this->hasMany(Hobby::class);
    }
}
