<?php

namespace Tripklik\BlueRibbonBags\Purchase\Request;

class Passenger
{
    public function __construct(
        public readonly int $orderSequence,
        public readonly string $lastName,
        public readonly string $firstName,
        public readonly string $email,
        public readonly string $airlineCode,
        public readonly string $airlineCodeType,
        public readonly string $airlineConfirmationNumber,
        public readonly string $phone,
        public readonly bool $sendSMS,
    ) {}

    public function toArray(): array
    {
        return [
            'OrderSequence' => $this->orderSequence,
            'LastName' => $this->lastName,
            'FirstName' => $this->firstName,
            'Email' => $this->email,
            'AirlineCode' => $this->airlineCode,
            'AirlineCodeType' => $this->airlineCodeType,
            'AirlineConfirmationNumber' => $this->airlineConfirmationNumber,
            'Phone' => $this->phone,
            'SendSMS' => $this->sendSMS,
        ];
    }
}
