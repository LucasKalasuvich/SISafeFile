<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Dokumen;

class DokumenTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['nama_dokumen'=>'Dokumen Administrasi','file_dokumen'=>'administrasi.pdf', 'upload_by'=>'1'],
            ['nama_dokumen'=>'Dokumen Kurikulum','file_dokumen'=>'kurikulum.pdf', 'upload_by'=>'2'],
        ];

        Dokumen::insert($data);
    }
}
