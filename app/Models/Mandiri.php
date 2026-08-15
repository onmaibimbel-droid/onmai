<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mandiri extends Model
{
    protected $fillable = ['nama_mapel', 'semester', 'kelas', 'pelajaran'];

    public function mapels()
    {
        return $this->hasMany(Mapel::class);
    }
        
}
