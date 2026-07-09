<?php

namespace Tests\Feature;

use App\Mail\ClaimSubmittedAdminMail;
use App\Mail\ClaimSubmittedUserMail;
use App\Mail\ContactSubmittedAdminMail;
use App\Mail\ContactSubmittedUserMail;
use App\Models\Claim;
use App\Models\ContactSubmission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PublicSubmissionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test contact submission works and triggers emails.
     */
    public function test_contact_submission_success()
    {
        Mail::fake();

        $response = $this->postJson('/contacto', [
            'name' => 'Juan Perez',
            'email' => 'juan.perez@gmail.com',
            'phone' => '987654321',
            'subject' => 'Consulta sobre lote de cacao',
            'message' => 'Hola, quisiera saber si tienen stock del lote especial de Ucayali.',
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('contact_submissions', [
            'name' => 'Juan Perez',
            'email' => 'juan.perez@gmail.com',
            'subject' => 'Consulta sobre lote de cacao',
        ]);

        Mail::assertSent(ContactSubmittedUserMail::class, function ($mail) {
            return $mail->submission->name === 'Juan Perez' && $mail->hasTo('juan.perez@gmail.com');
        });

        Mail::assertSent(ContactSubmittedAdminMail::class, function ($mail) {
            return $mail->submission->name === 'Juan Perez';
        });
    }

    /**
     * Test contact submission validation errors.
     */
    public function test_contact_submission_validation_fails()
    {
        $response = $this->postJson('/contacto', [
            'name' => '',
            'email' => 'invalid-email',
            'subject' => '',
            'message' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'email', 'subject', 'message']);
    }

    /**
     * Test Indecopi claim submission works and generates atomic code and mails.
     */
    public function test_claim_submission_success()
    {
        Mail::fake();

        $response = $this->postJson('/libro-de-reclamaciones', [
            'document_type' => 'DNI',
            'document_number' => '12345678',
            'full_name' => 'Maria Rodriguez',
            'email' => 'maria.rod@outlook.com',
            'phone' => '999888777',
            'address' => 'Av. Larco 123, Miraflores',
            'is_minor' => false,
            'type' => 'reclamacion',
            'claimed_amount' => 45.00,
            'product_service_description' => 'Chocolate con Hierba Luisa 70%',
            'claim_details' => 'El empaque llegó abierto y con el chocolate roto.',
            'consumer_request' => 'Reembolso del dinero o cambio del producto.',
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
        ]);

        $claim = Claim::first();
        $this->assertNotNull($claim);
        $this->assertEquals('RECL-' . now()->year . '-0001', $claim->claim_code);

        Mail::assertSent(ClaimSubmittedUserMail::class, function ($mail) use ($claim) {
            return $mail->claim->claim_code === $claim->claim_code && $mail->hasTo('maria.rod@outlook.com');
        });

        Mail::assertSent(ClaimSubmittedAdminMail::class, function ($mail) use ($claim) {
            return $mail->claim->claim_code === $claim->claim_code;
        });
    }

    /**
     * Test minor representative validation rules.
     */
    public function test_minor_claim_requires_representative_details()
    {
        $response = $this->postJson('/libro-de-reclamaciones', [
            'document_type' => 'DNI',
            'document_number' => '87654321',
            'full_name' => 'Pedrito Perez',
            'email' => 'pedrito@gmail.com',
            'phone' => '999999999',
            'address' => 'Jr. Puno 456',
            'is_minor' => true, // Claimant is a minor
            'type' => 'queja',
            'product_service_description' => 'Chocolate Munay',
            'claim_details' => 'Mala atencion en tienda.',
            'consumer_request' => 'Disculpas formales.',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'representative_name',
            'representative_document_type',
            'representative_document_number',
        ]);
    }
}
