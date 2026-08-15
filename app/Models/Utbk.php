<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Utbk extends Model
{
    protected $fillable = [
    'judulutbk_id',
    'pertanyaan',
    'opsi_a',
    'opsi_b',
    'opsi_c',
    'opsi_d',
    'opsi_e',
    'jawaban_benar'

];

    public function judulutbk()
    {
        return $this->belongsTo(Judulutbk::class);
    }

    
}
