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
                'key' => 'app.name',
                'value' => 'Flixarr',
                'created_at' => now(),
            ],
            [
                'key' => 'app.url',
                'value' => '/',
                'created_at' => now(),
            ],
            [
                'key' => 'setup.completed',
                'value' => false,
                'created_at' => now(),
            ],
            [
                'key' => 'plex.auth.token',
                'value' => null,
                'created_at' => now(),
            ],
        ]);
    }
}
