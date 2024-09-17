<?php

namespace Database\Factories;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Permission>
 */
class PermissionFactory extends Factory
{

    protected $model = Permission::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $permissions = [
            'create-candidates',
            'view-all-candidates',
            'view-assigned-candidates',
        ];

        return [
            'name' => fake()->randomElements($permissions)[0],
        ];
    }
}
