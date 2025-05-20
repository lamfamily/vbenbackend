<?php

namespace Modules\Leguo\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GoodsCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Leguo\App\Models\GoodsCategory::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $name = $this->faker->words(rand(3, 5), true);

        return [
            'name' => $name,
            'desc' => $this->faker->sentence,
            'status' => $this->faker->boolean(80),
        ];
    }
}

