<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->numberBetween(100, 10000);
        $gst_amount = $subtotal * (env('GST_AMOUNT') / 100);
        $discount_amount = fake()->numberBetween(0, 100) / 100;

        $discount_subtotal = $subtotal * $discount_amount;
        return [
            'invoice_number' => fake()->unique()->numberBetween(10, 1000),
            'invoice_date' => fake()->date('Y-m-d', now()),
            'client_name' => fake()->name(),
            'client_address' => fake()->address(),
            'remarks' => fake()->realText(),
            'discount_amount' => $discount_amount,
            'subtotal' => $subtotal,
            'gst_amount' => $gst_amount,
            'grand_total' => ($subtotal - $discount_subtotal) + $gst_amount,
        ];
    }
}
