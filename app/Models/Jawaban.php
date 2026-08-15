<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jawaban extends Model
{
     protected $fillable = [
        'user_id',
        'ujian_id',
        'soal_id',
        'jawaban'];

    public function soals()
    {
        return $this->belongsTo(Soal::class);
    }

}