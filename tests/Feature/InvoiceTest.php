<?php

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

it('validates items are required', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $data = Invoice::factory()->make()->toArray();
    unset($data['items']);

    $response = $this->postJson(route('invoices.store'), $data);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['items']);
});

it('validates items cannot be empty', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $data = Invoice::factory()->make()->toArray();
    $data['items'] = [];

    $response = $this->postJson(route('invoices.store'), $data);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['items']);
});

it('validates items structure', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $data = Invoice::factory()->make()->toArray();
    $data['items'] = [
        ['item_name' => '', 'item_quantity' => '', 'item_price' => '', 'item_amount' => ''],
    ];

    $response = $this->postJson(route('invoices.store'), $data);

    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'items.0.item_name',
            'items.0.item_quantity',
            'items.0.item_price',
            'items.0.item_amount',
        ]);
});


it('can create an invoice', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $payload = [
        'invoice_number' => 'INV24111501',
        'invoice_date' => '2024-11-15',
        'client_name' => 'John Doe',
        'client_address' => '123 Main St',
        'remarks' => 'Payment due in 30 days',
        'discount_amount' => 10,
        'subtotal' => 1000,
        'gst_amount' => 90,
        'grand_total' => 990,
        'items' => [
            ['item_name' => 'name', 'item_quantity' => '1', 'item_price' => '2', 'item_amount' => '2']
        ],
    ];

    $response = $this->postJson(route('invoices.store'), $payload);

    $response->assertStatus(200)
        ->assertJsonFragment(['invoice_number' => $payload['invoice_number']]);

    $this->assertDatabaseHas('invoices', ['invoice_number' => $payload['invoice_number']]);
});

it('can retrieve a specific invoice', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $invoice = Invoice::factory()->create();

    $response = $this->getJson(route('invoices.show', $invoice));

    $response->assertStatus(200)
        ->assertJsonFragment(['invoice_number' => $invoice->invoice_number]);
});

it('can update an invoice', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $invoice = Invoice::factory()->create();

    $updatePayload = [
        'client_name' => 'Jane Doe',
        'remarks' => 'Updated remarks',
        'items' => [
            ['item_name' => 'name', 'item_quantity' => '1', 'item_price' => '2', 'item_amount' => '2']
        ],
    ];

    $response = $this->putJson(route('invoices.update', $invoice), $updatePayload);

    $response->assertStatus(200)
        ->assertJsonFragment(['client_name' => $updatePayload['client_name']]);

    $this->assertDatabaseHas('invoices', ['client_name' => $updatePayload['client_name']]);
});

it('can delete an invoice', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $invoice = Invoice::factory()->create();

    $this->deleteJson(route('invoices.destroy', $invoice->id));

    $this->assertDatabaseMissing('invoices', ['id' => $invoice->id]);
});

it('can download invoice PDF', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $invoice = Invoice::factory()->create();

    $invoice->items()->createMany([
        ['item_name' => 'Item 1', 'item_quantity' => 1, 'item_price' => 100, 'item_amount' => 100],
        ['item_name' => 'Item 2', 'item_quantity' => 2, 'item_price' => 50, 'item_amount' => 100],
    ]);

    $response = $this->get(route('invoices.download', $invoice->id));

    $response->assertStatus(200);
});
