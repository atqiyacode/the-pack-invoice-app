<?php

namespace Database\Factories;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InvoiceItem>
 */
class InvoiceItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $invoice = Invoice::inRandomOrder()->first() ?? Invoice::factory()->create();
        $price = fake()->numberBetween(10, 100);
        $qty = fake()->numberBetween(1, 10);
        return [
            'invoice_id' => $invoice->id,
            'item_name' => fake()->name(),
            'item_price' => $price,
            'item_quantity' => $qty,
            'item_amount' => $price * $qty,
        ];
    }
}
