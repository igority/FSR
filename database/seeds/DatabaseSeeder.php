<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        /*
        $this->call([
          OrganizationsTableSeeder::class,
          //PostsTableSeeder::class,
          //CommentsTableSeeder::class,
        ]);
        */
        $this->call(OrganizationsSeeder::class);
        $this->call(LocationsSeeder::class);
        $this->call(DonorTypesSeeder::class);
    }
}
