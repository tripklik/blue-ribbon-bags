<?php

namespace Tripklik\BlueRibbonBags\Data;

class Currency
{
    public function __construct(
        public readonly string $currencyCode,
        public readonly string $currencyName,
        public readonly ?string $currencySymbol,
        public readonly float $rate,
        public readonly ?string $businessUnitPayoutFactor,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            currencyCode: $data['CurrencyCode'],
            currencyName: $data['CurrencyName'],
            currencySymbol: $data['CurrencySymbol'],
            rate: $data['Rate'],
            businessUnitPayoutFactor: $data['BusinessUnitPayoutFactor'],
        );
    }
}
