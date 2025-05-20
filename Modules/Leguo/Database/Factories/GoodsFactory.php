<?php

namespace Modules\Leguo\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Leguo\App\Models\Goods;

class GoodsFactory extends Factory
{
    protected $model = Goods::class;

    public function definition()
    {
        $name = $this->faker->words(rand(3, 5), true);

        return [
            'name' => $name,
            'desc' => $this->faker->sentence,
            'stock_num' => $this->faker->numberBetween(0, 100),
            'price' => $this->faker->randomFloat(2, 1, 100),
            'currency' => 'TWD',
            'status' => $this->faker->boolean(80),
        ];
    }
}
