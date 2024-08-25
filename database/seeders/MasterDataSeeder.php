<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds. bb
     */
    public function run()
    {
        //insert data ke tabel kategori_tiket
        DB::table('kategori_tiket')->insert([
            [
                'id' => 1,
                'jenis_kategori' => 'Request',
                'deskripsi' => 'request',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 2,
                'jenis_kategori' => 'Problem',
                'deskripsi' => 'problem',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 3,
                'jenis_kategori' => 'Request & Problem',
                'deskripsi' => 'request & problem',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);

        // Insert data ke tabel level
        DB::table('level')->insert([
            [
                'id' => 1,
                'jenis_level' => 'level I',
                'deskripsi' => 'resolved by Heldesk',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 2,
                'jenis_level' => 'level II',
                'deskripsi' => 'resolved by Engineer',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 3,
                'jenis_level' => 'level III',
                'deskripsi' => 'resolved by Leader Engineer',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 4,
                'jenis_level' => 'level Management',
                'deskripsi' => 'resolved by Management',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);

        //insert data ke tabel tipe_severity
        DB::table('tipe_severity')->insert([
            [
                'type' => 'Low',
                'deskripsi' => 'low',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'type' => 'Critical',
                'deskripsi' => 'medium',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'type' => 'High',
                'deskripsi' => 'high',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);

        DB::table('status')->insert([
            [
                'status' => 'New',
            ],
            [
                'status' => 'Inprogress',
            ],
            [
                'status' => 'On-Hold',
            ],
            [
                'status' => 'Closed',
            ],
        ]);
    }
}
