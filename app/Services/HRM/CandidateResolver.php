<?php

namespace App\Services\HRM;

use App\Models\Candidate;

class CandidateResolver
{
    public function resolve(array $payload): Candidate
    {
        $mobile = $this->normalizeMobile($payload['mobile'] ?? null);
        $email = $this->normalizeEmail($payload['email'] ?? null);

        $candidate = Candidate::query()
            ->when($mobile, fn ($query) => $query->orWhere('normalized_mobile', $mobile))
            ->when($email, fn ($query) => $query->orWhere('normalized_email', $email))
            ->first();

        $name = trim((string) ($payload['full_name'] ?? ($payload['first_name'] ?? '').' '.($payload['last_name'] ?? '')));

        $attributes = [
            'first_name' => $payload['first_name'] ?? null,
            'last_name' => $payload['last_name'] ?? null,
            'full_name' => $name !== '' ? $name : 'نامشخص',
            'mobile' => $payload['mobile'] ?? null,
            'normalized_mobile' => $mobile,
            'email' => $payload['email'] ?? null,
            'normalized_email' => $email,
            'city' => 'تهران',
            'current_job_title' => $payload['current_job_title'] ?? null,
            'current_company' => $payload['current_company'] ?? null,
            'linkedin_url' => $payload['linkedin_url'] ?? null,
            'portfolio_url' => $payload['portfolio_url'] ?? null,
        ];

        if ($candidate) {
            $candidate->fill(array_filter($attributes, fn ($value) => $value !== null));
            $candidate->save();

            return $candidate;
        }

        return Candidate::create($attributes);
    }

    public function normalizeMobile(?string $mobile): ?string
    {
        if (! $mobile) {
            return null;
        }

        $normalized = preg_replace('/\D+/', '', $mobile);
        if (! $normalized) {
            return null;
        }

        if (str_starts_with($normalized, '98')) {
            $normalized = '0'.substr($normalized, 2);
        }

        if (str_starts_with($normalized, '9')) {
            $normalized = '0'.$normalized;
        }

        return $normalized;
    }

    public function normalizeEmail(?string $email): ?string
    {
        if (! $email) {
            return null;
        }

        return mb_strtolower(trim($email));
    }
}
