<?php
use FSR\FoodType;
use Illuminate\Database\Seeder;

class FoodTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FoodType::create([
          'name' => 'Овошје',
          'comment' => '',
        ]);
        FoodType::create([
          'name' => 'Зеленчук',
          'comment' => '',
        ]);
        FoodType::create([
          'name' => 'Млечни производи',
          'comment' => '',
        ]);
        FoodType::create([
          'name' => 'Кондиторски производи',
          'comment' => '',
        ]);
        FoodType::create([
          'name' => 'Месна индустрија',
          'comment' => '',
        ]);
    }
}
