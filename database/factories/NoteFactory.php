<?php

namespace Database\Factories;

use App\Models\Note;
use Illuminate\Database\Eloquent\Factories\Factory;

class NoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Note::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'user_id'=> $this->faker->numberBetween(1,5),
            'count'=> $this->faker->numberBetween(1,20),
            'type' => 'income',
            'price' => 2000000,
            'date' => $this->faker->dateTimeBetween('-10 months', 'now'),
        ];
    }
}
