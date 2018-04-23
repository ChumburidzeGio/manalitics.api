<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::updateOrCreate([
            'email' => 'chumburidze.giorgi@outlook.com',
        ], [
            'name' => 'Giorgi Chumburidze',
            'password' => app('hash')->make('Superpass999')
        ]);
    }
}