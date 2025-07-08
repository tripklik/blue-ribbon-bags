<?php

namespace Tripklik\BlueRibbonBags\Purchase\Response;

use Illuminate\Contracts\Support\Arrayable;

class PurchaseResponse implements Arrayable
{
    public function __construct(
        public readonly string $serviceNumber,
        public readonly float $totalPrice,
        public readonly float $totalCharge,
        public readonly array $errors,
        public readonly bool $status,
        public readonly ?string $statusCode,
        public readonly array $warnings,
    ) {}

    public static function fromArray(array $response): self
    {
        return new self(
            serviceNumber: $response['ServiceNumber'] ?? '',
            totalPrice: (float) ($response['TotalPrice'] ?? 0),
            totalCharge: (float) ($response['TotalCharge'] ?? 0),
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
        return [
            'ServiceNumber' => $this->serviceNumber,
            'TotalPrice' => $this->totalPrice,
            'TotalCharge' => $this->totalCharge,
            'Errors' => $this->errors,
            'Status' => $this->status,
            'StatusCode' => $this->statusCode,
            'Warnings' => $this->warnings,
        ];
    }
}