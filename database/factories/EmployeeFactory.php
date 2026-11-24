<?php

namespace Database\Factories;

use App\Enums\EmployeeStatus;
use App\Enums\EmploymentType;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'gender' => fake()->randomElement(['male', 'female', 'other']),
            'dob' => fake()->date(),
            'national_id' => fake()->unique()->numerify('##########'),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'branch_id' => Branch::factory(),
            'department_id' => Department::factory(),
            'position_id' => Position::factory(),
            'join_date' => fake()->date(),
            'employment_type' => fake()->randomElement(
                array_column(EmploymentType::cases(), 'value')
            ),
            'employment_status' => fake()->randomElement(
                array_column(EmployeeStatus::cases(), 'value')
            ),
        ];
    }
}
