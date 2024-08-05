<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultSettings extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->insert([
            [
                'key' => 'app_name',
                'value' => 'Flixarr',
                'created_at' => now(),
            ],
            [
                'key' => 'app_url',
                'value' => '/',
                'created_at' => now(),
            ],
            [
                'key' => 'setup_completed',
                'value' => false,
                'created_at' => now(),
            ],
            [
                'key' => 'plex_token',
                'value' => null,
                'created_at' => now(),
            ],
        ]);
    }
}
