<?php

namespace Tripklik\BlueRibbonBags\Data;

class Product
{
    public function __construct(
        public readonly string $productCode,
        public readonly string $productName,
        public readonly float $productPrice,
        public readonly string $currencyCode,
        public readonly string $currencyName,
        public readonly float $bagCoverage,
        public readonly string $currencySymbol,
        public readonly ?string $alternateCurrency,
        public readonly ?string $caption,
        public readonly string $productTypeCode,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            productCode: $data['ProductCode'],
            productName: $data['ProductName'],
            productPrice: $data['ProductPrice'],
            currencyCode: $data['CurrencyCode'],
            currencyName: $data['CurrencyName'],
            bagCoverage: $data['BagCoverage'],
            currencySymbol: $data['CurrencySymbol'],
            alternateCurrency: $data['AlternateCurrency'],
            caption: $data['Caption'],
            productTypeCode: $data['ProductTypeCode'],
        );
    }
}
