<?php

namespace Database\Seeders;

use App\Models\Mandiri;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(['email' => 'user@example.com'], [
            'name' => 'User',
            'password' => Hash::make('password'),
            'role' => 'siswa',
        ]);

        User::updateOrCreate(['email' => 'guru@example.com'], [
            'name' => 'Guru Admin',
            'password' => Hash::make('guru1234'),
            'role' => 'guru',
        ]);

        $mandiri = Mandiri::updateOrCreate(['nama_mapel' => 'Matematika Wajib'], [
            'semester' => 1,
            'kelas' => 'X',
            'pelajaran' => 'Matematika',
        ]);

        foreach ([
            [
                'pertanyaan' => '<p>Hasil dari 2 + 3 adalah ...</p>',
                'a' => '<p>4</p>', 'b' => '<p>5</p>', 'c' => '<p>6</p>', 'd' => '<p>7</p>',
                'kunci' => 'B', 'pembahasan' => '<p>2 + 3 = 5.</p>',
            ],
            [
                'pertanyaan' => '<p>Jika <math xmlns="http://www.w3.org/1998/Math/MathML"><mrow><mi>x</mi><mo>+</mo><mn>3</mn></mrow></math> = 8, nilai x adalah ...</p>',
                'a' => '<p>3</p>', 'b' => '<p>4</p>', 'c' => '<p>5</p>', 'd' => '<p>6</p>',
                'kunci' => 'C', 'pembahasan' => '<p>Kurangi kedua ruas dengan 3, sehingga x = 5.</p>',
            ],
            [
                'pertanyaan' => '<p>Perhatikan tabel berikut.</p><table><tbody><tr><td>x</td><td>2</td></tr><tr><td>y</td><td>4</td></tr></tbody></table><p>Nilai y adalah ...</p>',
                'a' => '<p>2</p>', 'b' => '<p>3</p>', 'c' => '<p>4</p>', 'd' => '<p>5</p>',
                'kunci' => 'C', 'pembahasan' => '<p>Berdasarkan tabel, y bernilai 4.</p>',
            ],
        ] as $soal) {
            $mandiri->mapels()->firstOrCreate(['pertanyaan' => $soal['pertanyaan']], $soal);
        }
    }
}
