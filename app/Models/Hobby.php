<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hobby extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id', 'hobby',
    ];

    // function untuk relasi one to many
    public function members() {
        return $this->belongsTo(Member::class);
    }
}
