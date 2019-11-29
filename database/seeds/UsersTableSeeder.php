<?php

use Illuminate\Database\Seeder;
use App\User;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $email = 'warman.suganda@gmail.com';
        $password = 'secret';

        $this->command->line("");
        $this->command->line("Create Default User...");
        $user = User::where('email', $email)->first();
        $dataUser = [
            'name'  => "Warman Suganda",
            'email' => $email,
            'password'  => Hash::make($password),
            'email_verified_at' => now()
        ];

        if (!$user) {
            $user = User::create($dataUser);
        } else {
            $user->update($dataUser);
        }

        $this->command->line(" + Email: " .  $dataUser['email']);
        $this->command->line(" + Password: {$password}");
        $this->command->line("");
    }
}
