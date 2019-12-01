<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;
use App\Movie;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('en_US');
        $genre = ['Action', 'Horor', 'Comedy', 'Drama'];
        for($i = 1; $i <= 20; $i++){
            $randIndex = array_rand($genre);
            Movie::create([
                'title'        => $faker->sentence(6),
                'genre'        => $genre[$randIndex],
                'release_date' => $faker->date
            ]);
        }
    }
}
