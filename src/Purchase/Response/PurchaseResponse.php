<?php

namespace Tripklik\BlueRibbonBags\Purchase\Response;

class PurchaseResponse
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
}
