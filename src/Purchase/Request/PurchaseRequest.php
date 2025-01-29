<?php

namespace Tripklik\BlueRibbonBags\Purchase\Request;

use DateTime;

class PurchaseRequest
{
    public function __construct(
        public readonly string $productCode,
        public readonly bool $isInternational,
        public readonly string $promoCode,
        public readonly string $userLogin,
        public readonly string $userPassword,
        public readonly string $customerReferenceNumber,
        public readonly bool $replaceServiceNumberWithCRN,
        public readonly string $flightDetails,
        public readonly DateTime $departureDt,
        public readonly DateTime $lastArrivalDt,
        public readonly string $currencyCode,
        public readonly string $agentEmailSend,
        public readonly PassengersCollection $passengerList,
    ) {}

    public function toArray(): array
    {
        return [
            'ProductCode' => $this->productCode,
            'IsInternational' => $this->isInternational,
            'PromoCode' => $this->promoCode,
            'UserLogin' => $this->userLogin,
            'UserPassword' => $this->userPassword,
            'CustomerReferenceNumber' => $this->customerReferenceNumber,
            'ReplaceServiceNumberWithCRN' => $this->replaceServiceNumberWithCRN,
            'FlightDetails' => $this->flightDetails,
            'DepartureDt' => $this->departureDt->format('Y-m-d H:i:s'),
            'LastArrivalDt' => $this->lastArrivalDt->format('Y-m-d H:i:s'),
            'CurrencyCode' => $this->currencyCode,
            'AgentEmailSend' => $this->agentEmailSend,
            'PassengerList' => $this->passengerList->toArray(),
        ];
    }
}
