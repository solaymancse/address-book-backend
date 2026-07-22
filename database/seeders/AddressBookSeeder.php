<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AddressBook;
use App\Models\User;
use Faker\Factory as Faker;

class AddressBookSeeder extends Seeder {
    public function run(): void {
        $faker = Faker::create();
        $admin = User::first() ?? User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123'),
        ]);

        for ($i = 0; $i < 50; $i++) {
            AddressBook::create([
                'name' => $faker->name,
                'phone' => $faker->phoneNumber,
                'email' => $faker->unique()->safeEmail,
                'website' => $faker->url,
                'gender' => $faker->randomElement(['Male', 'Female', 'Other']),
                'age' => $faker->numberBetween(18, 80),
                'nationality' => $faker->country,
                'created_by' => $admin->id,
            ]);
        }
    }
}