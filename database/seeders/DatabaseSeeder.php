<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(kantorPusatLocation::class);
        $this->call(typeLocation::class);
        $this->call(version::class);
        $this->call(gpJamMasuk::class);
    }
}
