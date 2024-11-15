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

        $discount = fake()->numberBetween(0, 100);

        $discount_amount = $subtotal * $discount / 100;

        $gst_amount = ($subtotal - $discount_amount) * (env('GST_AMOUNT') / 100);

        return [
            'invoice_date' => fake()->date('Y-m-d', now()),
            'client_name' => fake()->name(),
            'client_address' => fake()->address(),
            'remarks' => fake()->realText(),
            'subtotal' => $subtotal,
            'discount' => $discount,
            'discount_amount' => $discount_amount,
            'gst' => env('GST_AMOUNT', 9),
            'gst_amount' => $gst_amount,
            'grand_total' => ($subtotal - $discount_amount) + $gst_amount,
        ];
    }
}
