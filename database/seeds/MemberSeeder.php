<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Member;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('en_US');

        for($i = 1; $i <= 50; $i++){
            Member::create([
                'name'      => $faker->name,
                'dob'       => $faker->date('Y-m-d', '-10 years'),
                'address'   => $faker->address,
                'telephone' => $faker->e164PhoneNumber,
                'identity'  => $faker->randomNumber(8),
                'join_date' => $faker->dateTimeBetween('-5 years')->format('Y-m-d'),
                'is_active' => $i < 45 ? 1 : 0
            ]);
        }
    }
}
