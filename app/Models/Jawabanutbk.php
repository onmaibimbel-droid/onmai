<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jawabanutbk extends Model
{
    protected $fillable = [
        'user_id',
        'judulutbk_id',
        'utbk_id',
        'jawaban'];

    public function utbk()
    {
        return $this->belongsTo(Utbk::class);
    }
}
