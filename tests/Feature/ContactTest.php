<?php

namespace Tests\Feature;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_should_be_able_to_create_a_new_contact(): void
    {
        $data = [
            'name' => 'Rodolfo Meri',
            'email' => 'rodolfomeri@contato.com',
            'phone' => '(41) 98899-4422'
        ];

        $response = $this->postJson('/api/contacts', $data);

        $response->assertStatus(201);

        $expected = $data;
        $expected['phone'] = preg_replace('/\D/', '', $expected['phone']);

        $this->assertDatabaseHas('contacts', $expected);
    }

    #[Test]
    public function it_should_validate_information(): void
    {
        $data = [
            'name' => 'ro',
            'email' => 'email-errado@',
            'phone' => '419'
        ];

        $response = $this->postJson('/api/contacts', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'name',
            'email',
            'phone'
        ]);
        $this->assertDatabaseCount('contacts', 0);
    }

    #[Test]
    public function it_should_be_able_to_list_contacts_paginated_by_10_items_per_page(): void
    {
        Contact::factory(20)->create();

        $response = $this->getJson('/api/contacts');

        $response->assertStatus(200);

        $data = $response->json('data');

        $this->assertCount(10, $data);
    }

    #[Test]
    public function it_should_be_able_to_delete_a_contact(): void
    {
        $contact = Contact::factory()->create();
        $response = $this->deleteJson("/api/contacts/{$contact->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('contacts', $contact->toArray());
    }

    #[Test]
    public function the_contact_email_should_be_unique(): void
    {
        $contact = Contact::factory()->create();

        $data = [
            'name' => 'Rodolfo Meri',
            'email' => $contact->email,
            'phone' => '(41) 98899-4422'
        ];

        $response = $this->postJson('/api/contacts', $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'email'
        ]);
        $this->assertDatabaseCount('contacts', 1);
    }

    #[Test]
    public function it_should_be_able_to_update_a_contact(): void
    {
        $contact = Contact::factory()->create();

        $data = [
            'name' => 'Rodolfo Meri',
            'email' => 'emailatualizado@email.com',
            'phone' => '(41) 98899-4422'
        ];

        $response = $this->putJson("/api/contacts/{$contact->id}", $data);

        $response->assertStatus(200);

        $expected = $data;
        $expected['phone'] = preg_replace('/\D/', '', $expected['phone']);

        $this->assertDatabaseHas('contacts', $expected);
        $this->assertDatabaseMissing('contacts', $contact->toArray());
    }
}
