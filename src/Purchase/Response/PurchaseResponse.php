<?php

namespace Tripklik\BlueRibbonBags\Purchase\Response;

use Illuminate\Contracts\Support\Arrayable;

class PurchaseResponse implements Arrayable
{
    public function __construct(
        public readonly string $serviceNumber,
        public readonly float $totalPrice,
        public readonly float $totalCharge,
        public readonly ?string $alternativeCurrencyCode,
        public readonly ?float $alternativeCurrencyTotalCharge,
        public readonly array $errors,
        public readonly bool $status,
        public readonly ?string $statusCode,
        public readonly array $warnings,
    ) {}

    public static function fromArray(array $response): self
    {

        return new self(
            serviceNumber: $response['Data']['ServiceNumber'] ?? '',
            totalPrice: (float) ($response['Data']['TotalPrice'] ?? 0),
            totalCharge: (float) ($response['Data']['TotalCharge'] ?? 0),
            alternativeCurrencyCode:  $response['Data']['AlternativeCurrencyCode'] ?? null,
            alternativeCurrencyTotalCharge: $response['Data']['AlternativeCurrencyTotalCharge'] ?? null,
            errors: $response['Errors'] ?? [],
            status: $response['Status'] ?? false,
            statusCode: $response['StatusCode'] ?? null,
            warnings: $response['Warnings'] ?? [],
        );
    }

    /**
     * Convert the instance to an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        $data = [
            'ServiceNumber' => $this->serviceNumber,
            'TotalPrice' => $this->totalPrice,
            'TotalCharge' => $this->totalCharge,
            'AlternativeCurrencyCode' => $this->alternativeCurrencyCode,
            'AlternativeCurrencyTotalCharge' => $this->alternativeCurrencyTotalCharge,
            'Errors' => $this->errors,
            'Status' => $this->status,
            'StatusCode' => $this->statusCode,
            'Warnings' => $this->warnings,
        ];

        return array_filter($data, function ($value) {
            return $value !== null && $value !== '';
        });
    }
}
