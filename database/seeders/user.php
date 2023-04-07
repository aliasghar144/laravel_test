<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class user extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        foreach (range(1,10) as $item){
            DB::table('users')->insert([
                'username'=> "user $item",
                'password'=> bcrypt('12345678'),
                'phone'=> random_int(1,150000000),
                'call' => random_int(0,1),
            ]);
        }
        DB::table('users')->insert([
            'username'=>'0850220556',
            'password'=>'$2y$10$FZ94NWIqqqIo5b7EeV32F.cFVHSRdSkON9FsXNMEOPys9FXXlDaSC',
            'phone'=>'09014482158',
        ]);
    }
}
