<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Judulutbk extends Model
{
    protected $fillable = ['judul', 'tahun'];

    public function utbks()
    {
        return $this->hasMany(Utbk::class);
    }
}

