<?php

namespace App\Services;

use App\Models\Customer;
use App\Support\IdentityDocument;
use App\Support\PhoneNumber;
use Illuminate\Support\Collection;

class CustomerService
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function findOrCreateFromPayload(array $data): Customer
    {
        $existing = $this->findExistingCustomer($data);

        if ($existing) {
            $this->fillMissingAttributes($existing, $data);
            $existing->save();

            return $existing;
        }

        return Customer::create($data);
    }

    public function findByPhone(string $phone): ?Customer
    {
        return $this->findByNormalizedPhone(PhoneNumber::normalize($phone));
    }

    public function findByPassportOrCin(string $passportOrCin): ?Customer
    {
        return $this->findByNormalizedPassportOrCin(IdentityDocument::normalize($passportOrCin));
    }

    /**
     * Merge duplicate customers by passport/CIN first, then by phone.
     *
     * @return int Number of duplicate records removed.
     */
    public function mergeDuplicates(): int
    {
        $removed = $this->mergeDuplicateGroups('passport_or_cin_normalized');
        $removed += $this->mergeDuplicateGroups('phone_normalized');

        return $removed;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function findExistingCustomer(array $data): ?Customer
    {
        $passportOrCin = isset($data['passport_or_cin']) ? (string) $data['passport_or_cin'] : '';
        $existingByPassport = $this->findByNormalizedPassportOrCin(IdentityDocument::normalize($passportOrCin));

        if ($existingByPassport) {
            return $existingByPassport;
        }

        $phone = isset($data['phone']) ? (string) $data['phone'] : '';

        return $this->findByNormalizedPhone(PhoneNumber::normalize($phone));
    }

    private function findByNormalizedPhone(string $normalizedPhone): ?Customer
    {
        if ($normalizedPhone === '') {
            return null;
        }

        return Customer::query()
            ->where('phone_normalized', $normalizedPhone)
            ->first();
    }

    private function findByNormalizedPassportOrCin(string $normalizedPassportOrCin): ?Customer
    {
        if ($normalizedPassportOrCin === '') {
            return null;
        }

        return Customer::query()
            ->where('passport_or_cin_normalized', $normalizedPassportOrCin)
            ->first();
    }

    private function mergeDuplicateGroups(string $field): int
    {
        $removed = 0;

        $groups = Customer::query()
            ->withCount('reservations')
            ->whereNotNull($field)
            ->where($field, '!=', '')
            ->get()
            ->groupBy($field)
            ->filter(fn (Collection $customers): bool => $customers->count() > 1);

        foreach ($groups as $customers) {
            $keeper = $customers
                ->sortBy([
                    fn (Customer $customer): int => -$customer->reservations_count,
                    fn (Customer $customer): int => $customer->id,
                ])
                ->first();

            if (! $keeper instanceof Customer) {
                continue;
            }

            foreach ($customers as $duplicate) {
                if ($duplicate->id === $keeper->id) {
                    continue;
                }

                $duplicate->reservations()->update(['customer_id' => $keeper->id]);
                $duplicate->documents()->update(['customer_id' => $keeper->id]);
                $this->fillMissingAttributes($keeper, $duplicate->only([
                    'full_name',
                    'nationality',
                    'phone',
                    'email',
                    'passport_or_cin',
                    'driving_license_number',
                ]));
                $keeper->save();
                $duplicate->delete();
                $removed++;
            }
        }

        return $removed;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function fillMissingAttributes(Customer $customer, array $data): void
    {
        foreach (['full_name', 'nationality', 'phone', 'email', 'passport_or_cin', 'driving_license_number'] as $field) {
            if (! array_key_exists($field, $data)) {
                continue;
            }

            $value = $data[$field];

            if ($value === null || $value === '') {
                continue;
            }

            $current = $customer->{$field};

            if ($current === null || $current === '') {
                $customer->{$field} = $value;
            }
        }
    }
}
