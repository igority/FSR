<?php
use FSR\QuantityType;
use Illuminate\Database\Seeder;

class QuantityTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        QuantityType::create([
          'name' => 'kg',
          'description' => 'килограми',
        ]);
        QuantityType::create([
          'name' => 'l',
          'description' => 'литри',
        ]);
        QuantityType::create([
          'name' => 'ml',
          'description' => 'милилитри',
        ]);
        QuantityType::create([
          'name' => 't',
          'description' => 'тони',
        ]);
        QuantityType::create([
          'name' => 'mg',
          'description' => 'милиграми',
        ]);
    }
}
