<?php

namespace App\Http\Controllers;

use App\Mail\ClaimSubmittedAdminMail;
use App\Mail\ClaimSubmittedUserMail;
use App\Mail\ContactSubmittedAdminMail;
use App\Mail\ContactSubmittedUserMail;
use App\Models\Claim;
use App\Models\ContactSubmission;
use App\Models\CompanyInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PublicSubmissionController extends Controller
{
    /**
     * Handle public contact submission.
     */
    public function submitContact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
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

        // Sanitization
        $data = $validator->validated();
        $data['name'] = strip_tags($data['name']);
        $data['subject'] = strip_tags($data['subject']);
        $data['message'] = strip_tags($data['message']);

        $submission = ContactSubmission::create($data);

        // Dispatch notifications
        try {
            $company = CompanyInfo::first();
            $adminEmail = $company?->contact_email_receiver ?: ($company?->email ?: config('mail.from.address', 'admin@dosaguas.com'));

            Mail::to($submission->email)->send(new ContactSubmittedUserMail($submission));
            Mail::to($adminEmail)->send(new ContactSubmittedAdminMail($submission));
        } catch (\Exception $e) {
            // Log mail failures, do not crash response
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
        $validator = Validator::make($request->all(), [
            'document_type' => 'required|string|in:DNI,CE,Pasaporte,RUC',
            'document_number' => 'required|string|max:25',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
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
        
        // Strip tag inputs
        $data['full_name'] = strip_tags($data['full_name']);
        $data['address'] = strip_tags($data['address']);
        $data['product_service_description'] = strip_tags($data['product_service_description']);
        $data['claim_details'] = strip_tags($data['claim_details']);
        $data['consumer_request'] = strip_tags($data['consumer_request']);
        if (isset($data['representative_name'])) {
            $data['representative_name'] = strip_tags($data['representative_name']);
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
            'message' => 'Reclamación registrada exitosamente.',
            'claim_code' => $claim->claim_code,
        ], 201);
    }
}
