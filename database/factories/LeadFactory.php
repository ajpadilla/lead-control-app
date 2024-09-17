<?php

namespace Database\Factories;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lead>
 */
class LeadFactory extends Factory
{

    protected $model = Lead::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name, // Genera un nombre falso
            'source' => fake()->randomElement(['website', 'social_media', 'referral']),
            'owner' => User::factory()->create()->id,
            'created_by' => User::factory()->create()->id,
            'created_at' => now(),
        ];
    }
}
