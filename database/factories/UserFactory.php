<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Domain\Cpf\CpfGenerator;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $cpfGenerator = new CpfGenerator();
        return [
            'uuid' => $this->faker->uuid,
            'name' => $this->faker->name,
            'cpf' =>  $cpfGenerator->generate(),
            'email' => $this->faker->unique()->safeEmail,
            'created_at' => date('Y-m-d H:i:s'),
        ];
    }
}
