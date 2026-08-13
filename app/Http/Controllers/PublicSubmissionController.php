<?php

namespace App\Http\Controllers;

use App\Mail\ClaimSubmittedAdminMail;
use App\Mail\ClaimSubmittedUserMail;
use App\Mail\ContactSubmittedAdminMail;
use App\Mail\ContactSubmittedUserMail;
use App\Models\Claim;
use App\Models\ContactSubmission;
use App\Models\CompanyInfo;
use App\Models\NewsletterSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PublicSubmissionController extends Controller
{
    /**
     * Helper to sanitize string inputs (removes control characters & HTML tags).
     */
    private function sanitizeInput(?string $value): ?string
    {
        if ($value === null) return null;
        // Strip control characters (null bytes, carriage returns, etc)
        $clean = preg_replace('/[\x00-\x1F\x7F]/', '', $value);
        return trim(strip_tags($clean));
    }

    /**
     * Helper to sanitize and normalize email inputs.
     */
    private function sanitizeEmail(?string $email): ?string
    {
        if (empty($email)) return null;
        $clean = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
        $clean = preg_replace('/[\x00-\x1F\x7F]/', '', $clean);
        return strtolower($clean);
    }

    /**
     * Handle public contact submission.
     */
    public function submitContact(Request $request)
    {
        // Anti-bot Honeypot check
        if (!empty($request->input('website_hp'))) {
            return response()->json(['success' => true, 'message' => 'Mensaje recibido.'], 200);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email:rfc|max:255',
            'phone' => 'nullable|string|max:50',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $data['name'] = $this->sanitizeInput($data['name']);
        $data['email'] = $this->sanitizeEmail($data['email']);
        $data['phone'] = $this->sanitizeInput($data['phone'] ?? null);
        $data['subject'] = $this->sanitizeInput($data['subject']);
        $data['message'] = $this->sanitizeInput($data['message']);

        $submission = ContactSubmission::create($data);

        // Dispatch notifications
        try {
            $company = CompanyInfo::first();
            $adminEmail = $company?->contact_email_receiver ?: ($company?->email ?: config('mail.from.address', 'admin@dosaguas.com'));

            Mail::to($submission->email)->send(new ContactSubmittedUserMail($submission));
            Mail::to($adminEmail)->send(new ContactSubmittedAdminMail($submission));
        } catch (\Exception $e) {
            logger()->error('Failed to send contact emails: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Mensaje recibido correctamente. Nos comunicaremos contigo pronto.',
            'id' => $submission->id,
        ], 201);
    }

    /**
     * Handle Indecopi complaints book submission.
     */
    public function submitClaim(Request $request)
    {
        // Anti-bot Honeypot check
        if (!empty($request->input('website_hp'))) {
            return response()->json(['success' => true, 'message' => 'Reclamación registrada.'], 200);
        }

        $validator = Validator::make($request->all(), [
            'document_type' => 'required|string|in:DNI,CE,Pasaporte,RUC',
            'document_number' => 'required|string|max:25',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email:rfc|max:255',
            'phone' => 'required|string|max:50',
            'address' => 'required|string|max:500',
            'is_minor' => 'boolean',
            'representative_name' => 'required_if:is_minor,true,1|nullable|string|max:255',
            'representative_document_type' => 'required_if:is_minor,true,1|nullable|string|in:DNI,CE,Pasaporte,RUC',
            'representative_document_number' => 'required_if:is_minor,true,1|nullable|string|max:25',
            'type' => 'required|string|in:reclamacion,queja',
            'claimed_amount' => 'nullable|numeric|min:0',
            'product_service_description' => 'required|string|max:5000',
            'claim_details' => 'required|string|max:5000',
            'consumer_request' => 'required|string|max:5000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        
        // Strip tags and sanitize inputs
        $data['full_name'] = $this->sanitizeInput($data['full_name']);
        $data['email'] = $this->sanitizeEmail($data['email']);
        $data['phone'] = $this->sanitizeInput($data['phone']);
        $data['address'] = $this->sanitizeInput($data['address']);
        $data['product_service_description'] = $this->sanitizeInput($data['product_service_description']);
        $data['claim_details'] = $this->sanitizeInput($data['claim_details']);
        $data['consumer_request'] = $this->sanitizeInput($data['consumer_request']);
        if (isset($data['representative_name'])) {
            $data['representative_name'] = $this->sanitizeInput($data['representative_name']);
        }

        // Generation of atomic claim code under transaction
        $year = now()->year;
        $prefix = $data['type'] === 'reclamacion' ? 'RECL' : 'QUEJ';

        $claim = DB::transaction(function () use ($data, $year, $prefix) {
            $lastClaim = Claim::whereYear('created_at', $year)
                ->where('claim_code', 'LIKE', "{$prefix}-{$year}-%")
                ->lockForUpdate()
                ->latest('id')
                ->first();

            $nextNum = 1;
            if ($lastClaim) {
                $parts = explode('-', $lastClaim->claim_code);
                $lastNum = (int) end($parts);
                $nextNum = $lastNum + 1;
            }

            $sequence = str_pad($nextNum, 4, '0', STR_PAD_LEFT);
            $data['claim_code'] = "{$prefix}-{$year}-{$sequence}";
            $data['status'] = 'pending';

            return Claim::create($data);
        });

        // Dispatch notifications
        try {
            $company = CompanyInfo::first();
            $adminEmail = $company?->contact_email_receiver ?: ($company?->email ?: config('mail.from.address', 'admin@dosaguas.com'));

            Mail::to($claim->email)->send(new ClaimSubmittedUserMail($claim));
            Mail::to($adminEmail)->send(new ClaimSubmittedAdminMail($claim));
        } catch (\Exception $e) {
            logger()->error('Failed to send claim confirmation emails: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Reclamación registrada exitosamente con código ' . $claim->claim_code,
            'claim_code' => $claim->claim_code,
        ], 201);
    }

    /**
     * Handle public newsletter subscription with anti-bot & security validation.
     */
    public function submitNewsletter(Request $request)
    {
        // Anti-bot Honeypot check
        if (!empty($request->input('website_hp'))) {
            return response()->json(['success' => true, 'message' => 'Suscripción realizada.'], 200);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required|email:rfc|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => app()->getLocale() === 'es' ? 'Por favor ingrese un correo válido.' : 'Please enter a valid email address.',
            ], 422);
        }

        $email = $this->sanitizeEmail($request->input('email'));

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'success' => false,
                'message' => app()->getLocale() === 'es' ? 'El formato del correo electrónico no es válido.' : 'Invalid email format.',
            ], 422);
        }

        $subscription = NewsletterSubscription::firstOrCreate(
            ['email' => $email],
            ['subscribed_at' => now(), 'is_active' => true]
        );

        if (!$subscription->is_active) {
            $subscription->update(['is_active' => true, 'subscribed_at' => now()]);
        }

        $msg = app()->getLocale() === 'es'
            ? '¡Gracias por suscribirte! Recibirás nuestras novedades y promociones exclusivas.'
            : (app()->getLocale() === 'de'
                ? 'Vielen Dank für Ihre Anmeldung! Sie erhalten in Kürze exklusive Angebote.'
                : 'Thank you for subscribing! You will receive exclusive offers and news soon.');

        return response()->json([
            'success' => true,
            'message' => $msg,
        ], 200);
    }
}
