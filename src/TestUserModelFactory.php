<?php

namespace Shawnreid\LaravelQuickbooks;

use Illuminate\Database\Eloquent\Factories\Factory;

class TestUserModelFactory extends Factory
{
    protected $model = TestUserModel::class;

    public function definition()
    {
        return [
            'name'              => $this->faker->name,
            'email'             => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password'          => bcrypt('password'),
            'remember_token'    => \Illuminate\Support\Str::random(10),
        ];
    }
}
