<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ho_ten'            => fake()->name(),
            'ten_dang_nhap'     => fake()->unique()->userName(),
            'email'             => fake()->unique()->safeEmail(),
            'so_dien_thoai'     => '09' . fake()->numerify('########'),
            'email_verified_at' => now(),
            'mat_khau'          => static::$password ??= Hash::make('password'),
            'quyen_han'         => User::USER,
            'kich_hoat'         => true,
            'remember_token'    => Str::random(10),
        ];
    }

    /** Khách hàng thường (mặc định) */
    public function user(): static
    {
        return $this->state(fn (array $attributes) => [
            'quyen_han' => User::USER,
        ]);
    }

    /** Nhân viên */
    public function staff(): static
    {
        return $this->state(fn (array $attributes) => [
            'quyen_han' => User::STAFF,
        ]);
    }

    /** Kế toán */
    public function ketoan(): static
    {
        return $this->state(fn (array $attributes) => [
            'quyen_han' => User::KETOAN,
        ]);
    }

    /** Giám đốc / Admin */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'quyen_han' => User::ADMIN,
        ]);
    }

    /** Tài khoản bị khóa */
    public function locked(): static
    {
        return $this->state(fn (array $attributes) => [
            'kich_hoat' => false,
        ]);
    }
}