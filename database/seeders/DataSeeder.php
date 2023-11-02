<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use FakerRestaurant\Provider\en_US\Restaurant as Faker;
use Illuminate\Support\Facades\DB;

class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        $faker->addProvider(new Faker($faker));

        foreach (range(1, 10) as $index) {
            DB::table('foods')->insert([
                'title' => $faker->foodName(),
                'category_id' => $faker->numberBetween(1, 10),
            ]);
        }

        foreach (range(1, 10) as $index) {
            DB::table('ingredients')->insert([
                'title' => $faker->meatName(),
                'slug' => $faker->slug
            ]);
        }

        foreach (range(1, 10) as $index) {
            DB::table('tags')->insert([
                'title' => $faker->fruitName(),
                'slug' => $faker->slug
            ]);
        }

        foreach (range(1, 10) as $index) {
            DB::table('categories')->insert([
                'title' => $faker->dairyName(),
                'slug' => $faker->slug
            ]);
        }

        foreach (range(1, 30) as $index) {
            DB::table('food_ingredient')->insert([
                'food_id' => $faker->numberBetween(1, 10),
                'ingredient_id' => $faker->numberBetween(1, 10)
            ]);
        }

        foreach (range(1, 30) as $index) {
            DB::table('food_tag')->insert([
                'food_id' => $faker->numberBetween(1, 10),
                'tag_id' => $faker->numberBetween(1, 10)
            ]);
        }
    }
}

